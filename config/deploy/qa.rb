set :git_enable_submodules, 1

#adding line to test stuff
set(:user) { "stateadm" }

set(:domain) { "comapp401p.qa.ch4.s.com" }
set(:application) { "communities" }
set(:repository) { "git@github.com:dasfisch/communities.git" }

ssh_options[:forward_agent] = true
default_run_options[:pty] = true

##### APPLICATION #####
role :web, "comapp401p.qa.ch4.s.com", "comapp402p.qa.ch4.s.com"  # Your HTTP server, Apache/etc
role :app, "#{domain}"  # This may be the same as your `Web` server
role :db, domain, :primary => true

set (:branch) { "development" }
set (:deploy_to) { "/opt/stateadm/communities" }
set (:app_loc) { "/appl/wordpress/www/comm_new/community/wp-content" }

set :copy_strategy, :checkout
set :deploy_via, :copy

set :branch do
  default_tag = `git tag`.split("\n").last

  tag = Capistrano::CLI.ui.ask "Tag to deploy (make sure to push the tag first): [#{default_tag}] "
  tag = default_tag if tag.empty?
  tag
end

set :move_wp_content do
  run "rm -rf #{app_loc}/plugins/* && rm -rf #{app_loc}/themes/*"
  run "cp -R #{release_path}/wordpress/wp-content/plugins/* #{app_loc}/plugins"
  run "cp -R #{release_path}/wordpress/wp-content/themes/* #{app_loc}/themes"
end
