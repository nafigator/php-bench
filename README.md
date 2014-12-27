php-bench
=========

Console tools for benchmark PHP algorithms.

![Alt php-bench](https://github.com/nafigator/php-bench/raw/master/screen.png)

###Requirements:

* PHP CLI 5.4+
* Bash shell

###Installation:

	git clone https://github.com/nafigator/php-bench.git .
	git submodule init
	git submodule update

###Usage:

Create copy of test executable and modify your local settings:

    cp test-example test

Show available tests:

	./test-list
Create test skeleton:

    ./test-new <Test name> [<Block count>]
**Test name** - name of new test class.<br>
**Block count** - how much test blocks generate

Run test:

	./test <Class>

**Class** - class name from Tests directory.

###Examples:

	./test CloneVsNew
	./test IncludeVsRequire


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/nafigator/php-bench/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

