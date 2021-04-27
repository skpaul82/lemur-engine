# How to contribute

Thank you so much for helping with the Lemur Engine.

You can help in a number of ways;

* Bug fixes
* New AIML Tags
* New Features

If you haven't already, checkout our social page [Lemur Engine Socials](https://lemurengine.com/social.html). From here you will be able to join our dev-channel on the Discord server.

## New Branches

Please create a branch from develop use the following naming convention

For a new feature/tag:

    'feature/name-of-your-feature' 

For a bug fix:

    'fix/name-of-the-bug' 

## Coding Standards 

The project uses the PSR-2 coding style. [php-fig.org/psr/psr-2/] (https://www.php-fig.org/psr/psr-2/)

You can check that your code passes the coding standards by running

     php ./vendor/bin/phpcs

And classes are loaded using the PSR-4 autoloading standard [php-fig.org/psr/psr-4/] (https://www.php-fig.org/psr/psr-4/)

You can check that your code will autoload according to the standard by running

     composer dump-autoload -o
     
Comment comment comment - we love well commented code which considers the person reading it next. Please add as many comments as you feel is necessary for people to understand what you have written.     

## Testing

We currently have a range of PHPUnit and feature tests in the tests directory.

New features should have supporting tests written.

All tests should pass before any changes will be considered for merge.

    php artisan test --testsuite=All    

## Submitting changes

Please send a [GitHub Pull Request](https://github.com/theramenrobotdiscocode/lemur-engine/compare) to the develop branch with a clear list of what you've done (read more about [pull requests](http://help.github.com/pull-requests/)). 

Your pull request message will become your squashed commit message so make it as descriptive as you want.
