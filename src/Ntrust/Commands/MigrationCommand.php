<?php namespace Klaravel\Ntrust\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class MigrationCommand extends Command
{
    /**
     * Selected profile for generate
     * 
     * @var string
     */
    private $profile;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'ntrust:migration {profile=user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the Ntrust specifications.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->profile = $this->argument('profile');

        // check valid profile
        if (!Config::get('ntrust.profiles.' . $this->profile))
        {
            $this->error('Invalid profile. Please check profiles in config/ntrust.php');
            return;
        }

        $this->line(substr(__DIR__, 0, -8).'views');

        $this->laravel->view->addNamespace('ntrust', substr(__DIR__, 0, -15).'views');

        $rolesTable          = Config::get('ntrust.profiles.'. $this->profile .'.roles_table');
        $roleUserTable       = Config::get('ntrust.profiles.'. $this->profile .'.role_user_table');
        $permissionsTable    = Config::get('ntrust.profiles.'. $this->profile .'.permissions_table');
        $permissionRoleTable = Config::get('ntrust.profiles.'. $this->profile .'.permission_role_table');

        $this->line('');
        $this->info( "Tables: $rolesTable, $roleUserTable, $permissionsTable, $permissionRoleTable" );

        $message = "A migration that creates '$rolesTable', '$roleUserTable', '$permissionsTable', '$permissionRoleTable'".
            " tables will be created in database/migrations directory";

        $this->comment($message);
        $this->line('');

        if ($this->confirm("Proceed with the migration creation? [Yes|no]", "Yes")) {

            $this->line('');

            $this->info("Creating migration...");
            if ($this->createMigration($rolesTable, $roleUserTable, $permissionsTable, $permissionRoleTable)) {

                $this->info("Migration successfully created!");
            } else {
                $this->error(
                    "Couldn't create migration.\n Check the write permissions".
                    " within the database/migrations directory."
                );
            }

            $this->line('');

        }
    }

    /**
     * Create the migration.
     *
     * @return bool
     */
    protected function createMigration($rolesTable, $roleUserTable, $permissionsTable, $permissionRoleTable)
    {
        $migrationFile = base_path("/database/migrations")."/".date('Y_m_d_His'). "_" . 
            $this->profile ."_ntrust_setup_tables.php";

        $usersTable  = Config::get('ntrust.profiles.' . $this->profile . '.table');
        $userModel   = Config::get('ntrust.profiles.' . $this->profile . '.model');
        $userKeyName = (new $userModel())->getKeyName();
        $profile = $this->profile;

        $data = compact('rolesTable', 'roleUserTable', 'permissionsTable', 'permissionRoleTable', 'usersTable', 'userKeyName', 'profile');

        $output = $this->laravel->view->make('ntrust::generators.migration')->with($data)->render();

        if (!file_exists($migrationFile) && $fs = fopen($migrationFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }
}