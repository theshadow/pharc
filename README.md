# pharc the PHP Phar Compiler

Pharc is a project tool used to compile your PHP applications into a [phar](http://php.net/Phar) file. It does this by
taking a file which specifies how to combine the files and then producing the requested output file.

**This is not the same as compiling into machine code**

## Anatomy of a PHAR produced with Pharc

<pre>
+---------------------+
| Project Files       |
+---------------------+
| (Misc Files)        |
+---------------------+
| (Composer/Autoload) |
+---------------------+
| Stub                |
+---------------------+
| License             |
+---------------------+
</pre>

## Example pharc.yml

````yaml
targets:
  default:
    phar: composer.phar     # resulting output file
    bin: bin/composer       # Where the entry point is located
    license: LICENSE        # The license file to include
    signature-method: SHA1  # Signing not yet implemented.
    dependencies: composer  # Specify that dependencies are managed by composer
    composer:               
      config: composer.json # this whole section defines how dependencies should be included/excluded
      paths: vendor/
      include-dev: false
      exclude:
        - Tests
        - test
        - docs
      names:
        - "*.php"
        - LICENSE
    files:                              # file groups define paths and files to include the the phar
      project:                          # unique name to identify the file group
        paths: src/                     # paths to include can be single entry or multiple 
        ignore-vcs: true                # defaults to true, .git, .cvs, etc.. will be ignored
        strip: true                     # defaults to true, states the files should be stripped of whitespace
        names:                          # name matches
          - "*.php"
        excluded-names:                 # optional, min items: 1
          - Compiler.php
          - Classloader.php
      class-loader:
        paths: 
          - src/Composer/Autoload/
        names: 
          - Classloader.php
      hidden-input-exe:
        paths: vendor/seld/cli-prompt/res/
        names: 
          - hiddeninput.exe
        strip: false
      resources:
        paths: res/
        names: 
          - "*.json"
        strip: false
````

### Project Files

Project files encompass all the files that contain the business logic for your application. Such as your models, views,
and controllers.

### Misc Files (optional)

Are files such as images, json files, or other project resources you needed included. For example composer includes
a windows binary because input is difficult.

### Composer/Autoload Files (optional)

If you're build is configured to include composer it will attempt to load the JSON file and include the dependencies
from the configured vendor path. You may also, for development builds, include the require-dev dependencies.

### Stub

The stub is the executable entry point for your script. As an example, you can see one below. In it you can note that
basically it loads the phar into memory and then includes the command script from it. An example is seen in the composer
project which specifies **composer.phar** and **/bin/composer** respectively. In fact I stole their stub wholesale.
<pre>
#!/usr/bin/env php
<?php
/*
 * This file is part of Pharc.
 *
 * (c) Xander Guzman <xander.guzman@xanderguzman.com>
 *
 * For the full copyright and license information, please view
 * the license that is located at the bottom of this file.
 */
// Avoid APC causing random fatal errors per https://github.com/composer/composer/issues/264
if (extension_loaded('apc') && ini_get('apc.enable_cli') && ini_get('apc.cache_by_default')) {
    if (version_compare(phpversion('apc'), '3.0.12', '>=')) {
        ini_set('apc.cache_by_default', 0);
    } else {
        fwrite(STDERR, 'Warning: APC <= 3.0.12 may cause fatal errors when running composer commands.'.PHP_EOL);
        fwrite(STDERR, 'Update APC, or set apc.enable_cli or apc.cache_by_default to 0 in your php.ini.'.PHP_EOL);
    }
}
Phar::mapPhar('%%COMPOSER_FILE%%');
require 'phar://%%COMPOSER_FILE%%%%BIN_PATH%%';
__HALT_COMPILER();
</pre>

### License File

Lastly, the license file from the project is included at the end of the file. You can, technically, include an empty
file as a placeholder but should be replaced with an actual license before releasing your code into the wild.