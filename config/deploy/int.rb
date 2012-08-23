#adding line to test stuff
set(:user) { "stateadm" }

set(:domain) { "uxint" }
set(:application) { "communities" }
set(:repository) { "git@github.com:dasfisch/communities.git" }

ssh_options[:forward_agent] = true
default_run_options[:pty] = true

##### APPLICATION #####
role :web, "#{domain}"  # Your HTTP server, Apache/etc
role :app, "#{domain}"  # This may be the same as your `Web` server
role :db, domain, :primary => true

set (:branch) { "development" }
set (:deploy_to) { "/opt/stateapps/communities" }
set (:app_loc) { "/appl/communities/www/wp-content" }
set :deploy_via, :remote_cache

set :branch do
  default_tag = `git tag`.split("\n").last

  tag = Capistrano::CLI.ui.ask "Tag to deploy (make sure to push the tag first): [#{default_tag}] "
  tag = default_tag if tag.empty?
  tag
end

set :move_wp_content do
  run "rm -rf #{app_loc}/plugins/* && rm -rf #{app_loc}/themes/*"
  run "cp -R #{deploy_to}/current/wordpress/wp-content/plugins/* #{app_loc}/plugins"
  run "cp -R #{deploy_to}/current/wordpress/wp-content/themes/* #{app_loc}/themes"
end
