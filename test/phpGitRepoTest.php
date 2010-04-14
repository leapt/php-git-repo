<?php

require_once dirname(__FILE__).'/vendor/lime.php';
require_once dirname(__FILE__).'/phpGitRepoTestHelper.php';

$t = new lime_test();

$repo = _createTmpGitRepo($t);

$t->is($repo->run("branch"), '', '$repo->run("branch") returns nothing');

$t->is_deeply($repo->getBranches(), array(), 'No branches');

$t->is($repo->getCurrentBranch(), null, 'No current branch');

$t->is($repo->hasBranch('master'), false, 'No master branch');

try
{
  $repo->run('checkout master');
  $t->fail('Can not checkout master');
}
catch(RuntimeException $e)
{
  $t->pass('Can not checkout master');
}

$repo->run('remote add origin git://github.com/ornicar/php-git-repo.git');

$repo->run('pull origin master');

$t->is_deeply($repo->getBranches(), array('master'), 'One branch master');

$t->is($repo->hasBranch('master'), true, 'master branch exists');

$t->is($repo->getCurrentBranch(), 'master', 'Current branch: master');

$repo->run('checkout -b other_branch');

$t->is_deeply($repo->getBranches(), array('master', 'other_branch'), 'Two branches, master and other_branch');

$t->is($repo->getCurrentBranch(), 'other_branch', 'Current branch: other_branch');

$t->is($repo->hasBranch('other_branch'), true, 'other_branch branch exists');

$repo->run('checkout master');

$t->is($repo->getCurrentBranch(), 'master', 'Current branch: master');

try
{
  $repo->run('wtf');
  $t->fail('wtf is not a valid command');
}
catch(RuntimeException $e)
{
  $t->pass('wtf is not a valid command');
}