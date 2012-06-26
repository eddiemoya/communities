set(:user) { "sfrohm" }

set(:domain) { "uxdev" }
set(:application) { "uxwordpress" }
set(:repository) { "ssh://git@uxdev/home/git/repos/communities.git" }

##### APPLICATION #####
role :web, "#{domain}"  # Your HTTP server, Apache/etc
role :app, "#{domain}"  # This may be the same as your `Web` server
role :db, domain, :primary => true

set (:branch) { "development" }
set (:deploy_to) { "/usr/share/apps/communities" }
set (:app_loc) { "/appl/communities/www/wp-content" }

set :move_wp_content do
  run "rm -rf #{app_loc}/plugins/* && #{app_loc}/themes/*"
  run "cp -R #{deploy_to}/current/wordpress/wp-content/plugins/* #{app_loc}/plugins"
  run "cp -R #{deploy_to}/current/wordpress/wp-content/themes/* #{app_loc}/themes"
end
