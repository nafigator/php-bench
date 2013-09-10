php-bench
=========

Tools for benchmark PHP algorithms.

![Alt php-bench](https://github.com/nafigator/php-bench/raw/master/screen.png)

###Requirements:

* PHP CLI 5.3+
* Bash shell

###Installation:

	git clone https://github.com/nafigator/php-bench.git .
	git submodule init
	git submodule update

###Usage:

	./test <Class>

**Class** - class name from Tests directory (except TestApplication).

###Examples:

	./test CloneVsNew
	./test IncludeVsRequire
