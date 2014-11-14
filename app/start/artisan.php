<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/
Artisan::add(new ImportDataCLI);
Artisan::add(new SendScheduledNewsletter);
Artisan::add(new CheckValidEmailByMandrill);
Artisan::add(new CheckValidEmailByMailgun);
Artisan::add(new CheckValidEmailManually);
Artisan::add(new TemporarySendNewsletterCommand);
Artisan::add(new TemporarySendAWSNewsletter);
Artisan::add(new TemporarySendMandrillNewsletter);