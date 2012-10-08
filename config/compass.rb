# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "wordpress/wp-content/themes/Eris/"
sass_dir = "wordpress/wp-content/themes/Eris/assets/sass/"
images_dir = "wordpress/wp-content/themes/Eris/assets/img/"
javascripts_dir = "wordpress/wp-content/themes/Eris/assets/js/"

Sass::Script::Number.precision = 15

asset_cache_buster do |path, real_path|
  if File.exists?(real_path)
    pathname = Pathname.new(path)
    modified_time = File.mtime(real_path).strftime("%s")
    new_path = "%s/%s%s?v=%s" % [pathname.dirname, pathname.basename(pathname.extname), pathname.extname, modified_time]

    {:path => new_path, :query => nil}
  end
end

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
output_style = (environment == :production) ? :compressed : :expanded

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false


# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass