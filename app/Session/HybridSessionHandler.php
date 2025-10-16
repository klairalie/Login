<?php

namespace App\Session;

use SessionHandlerInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Session\FileSessionHandler;
use Illuminate\Filesystem\Filesystem;

class HybridSessionHandler implements SessionHandlerInterface
{
    protected FileSessionHandler $fileHandler;
    protected string $table;

    /**
     * HybridSessionHandler constructor.
     *
     * @param string $table Central DB table name
     */
    public function __construct(string $table = 'central_sessions')
    {
        $this->table = $table;

        // Local file storage
        $filesystem = new Filesystem();
        $path = storage_path('framework/sessions');
        $lifetime = config('session.lifetime', 120); // minutes

        $this->fileHandler = new FileSessionHandler($filesystem, $path, $lifetime, true);
    }

    /** 
     * Dummy open() - required by SessionHandlerInterface 
     */
    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    /** 
     * Dummy close() - required by SessionHandlerInterface 
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Read session data
     */
    public function read(string $sessionId): string
    {
        // Try local file first
        $data = $this->fileHandler->read($sessionId);

        if (empty($data)) {
            // Fallback: read from central DB
            $row = DB::connection('capstone_central')->table($this->table)
                ->where('id', $sessionId)
                ->first();

            $data = $row ? $row->payload : '';
        }

        return $data;
    }

    /**
     * Write session data
     */
   public function write(string $sessionId, string $data): bool
{
    $timestamp = time();

    // Always write to local file
    $this->fileHandler->write($sessionId, $data);

    // Only write to central DB if payload is meaningful
    if (!empty($data) && $data !== serialize([])) {
        DB::connection('capstone_central')->table($this->table)
            ->updateOrInsert(
                ['id' => $sessionId],
                ['payload' => $data, 'last_activity' => $timestamp]
            );
    }

    // Always return true for PHP session compatibility
    return true;
}



    /**
     * Destroy a session
     */
    public function destroy(string $sessionId): bool
    {
        // Delete local file
        $this->fileHandler->destroy($sessionId);

        // Delete from central DB
        DB::connection('capstone_central')->table($this->table)
            ->where('id', $sessionId)
            ->delete();

        return true;
    }

    /**
     * Garbage collection
     */
    public function gc(int $maxLifetime): int
    {
        // Clean local files
        $this->fileHandler->gc($maxLifetime);

        // Clean central DB
        $past = time() - $maxLifetime; // seconds
        DB::connection('capstone_central')->table($this->table)
            ->where('last_activity', '<', $past)
            ->delete();

        return 1;
    }
}
