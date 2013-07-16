#adding line to test stuff
set(:user) { "stateadm" }
 
set(:domain) { "shcphp" }
set(:application) { "communities" }
set(:repository) { "git@github.com:dasfisch/communities.git" }
 
ssh_options[:forward_agent] = true
default_run_options[:pty] = true
 
##### APPLICATION #####
role :web, "#{domain}"  # Your HTTP server, Apache/etc
role :app, "#{domain}"  # This may be the same as your `Web` server
role :db, domain, :primary => true
 
 
set :branch do
  current_branch = `git branch`.match(/\* (\S+)\s/m)[1]
 
  branch = Capistrano::CLI.ui.ask "Which branch would you like to deploy to INT with WP 3.6? (current branch: [#{current_branch}]) "
  branch = current_branch if branch.empty?
  branch
end
 
set (:deploy_to) { "/home/stateadm/apps/comm36" }
set (:app_loc) { "/var/www/comm36/community/wp-content" }
set :deploy_via, :remote_cache
 
set :move_wp_content do
  run "rm -rf #{app_loc}/plugins/* && rm -rf #{app_loc}/themes/*"
  run "cp -R #{deploy_to}/current/wordpress/wp-content/plugins/* #{app_loc}/plugins"
  run "cp -R #{deploy_to}/current/wordpress/wp-content/themes/* #{app_loc}/themes"
end