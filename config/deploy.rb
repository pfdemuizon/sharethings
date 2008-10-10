set :application, "sharethings"
set :repository,  "git@github.com:pfdemuizon/sharethings.git"

set :user, "pfdemuizon"
set :group, "pfdemuizon"

# use git
set :scm, :git
set :git_enable_submodules, 1

set :deploy_to, "/home/pfdemuizon/#{application}"

set :deploy_via, :remote_cache
set :keep_releases, 10
set :use_sudo, false

role :app, "graspbirdstail.com"
role :web, "graspbirdstail.com"
role :db,  "graspbirdstail.com", :primary => true

after "deploy:update_code", "deploy:symlink_shared"

namespace :deploy do
  task :symlink_shared, :roles => :app, :except => {:no_symlink => true} do
    invoke_command "ln -nfs #{shared_path}/config/database.yml #{release_path}/config/database.yml"
    invoke_command "ln -nfs #{shared_path}/config/config.php #{release_path}/public/php/config.php"
    invoke_command "ln -nfs #{shared_path}/tmp #{release_path}/tmp"
    invoke_command "ln -nfs #{shared_path}/pids #{release_path}/pids"
  end
  task :version do
    invoke_command "git --version"
    invoke_command "echo $PATH"
  end
  task :restart do
    invoke_command "touch #{release_path}/tmp/restart.txt"
  end
end
