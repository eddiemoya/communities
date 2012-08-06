#adding line to test stuff
set(:user) { "stateadm" }

set(:domain) { "uxint" }
set(:application) { "communities" }
set(:repository) { "git@github.com:dasfisch/communities.git" }
set(:git_enable_submodules) { 1 }


##### APPLICATION #####
role :web, "#{domain}"  # Your HTTP server, Apache/etc
role :app, "#{domain}"  # This may be the same as your `Web` server
role :db, domain, :primary => true

set (:branch) { "development" }
set (:deploy_to) { "/home/stateadm/communities" }
set (:app_loc) { "/appl/communities/www/wp-content" }

set :move_wp_content do
  run "rm -rf #{app_loc}/plugins/* && rm -rf #{app_loc}/themes/*"
  run "cp -R #{deploy_to}/current/wordpress/wp-content/plugins/* #{app_loc}/plugins"
  run "cp -R #{deploy_to}/current/wordpress/wp-content/themes/* #{app_loc}/themes"
end
