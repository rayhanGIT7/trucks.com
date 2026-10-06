<?php

// Import trucks from the Truck API from the command line (or a cron job):
//   php scripts/sync-trucks.php

use App\Services\TruckSyncService;

require __DIR__ . '/../app/bootstrap.php';

$result = (new TruckSyncService())->sync();
echo "Created: {$result['created']}, updated: {$result['updated']}, skipped: {$result['skipped']}\n";
