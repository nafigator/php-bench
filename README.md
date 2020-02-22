[![GitHub license][License img]][License src]

php-bench
=========
**Console tools for benchmark PHP algorithms.**

![Alt php-bench](https://github.com/nafigator/php-bench/raw/master/screen.png)

### Requirements:

* PHP CLI 5.4+

### Installation:

```bash
git clone https://github.com/nafigator/php-bench.git
cd php-bench
git submodule update --init
```

### Usage:

Create copy of test executable and modify your local settings:

```text
cp test-example test
```


Show available tests:

```text
./test-list
```

Create test skeleton:

```text
./test-new <Test name> [<Block count>]
```
**Test name** - name of new test class.<br>
**Block count** - how much test blocks generate

Run test:

```text
./test <Class>
```


**Class** - class name from Tests directory.

### Examples:

```text
./test CloneVsNew
./test IncludeVsRequire
```


### Run all tests:

```bash
for name in $(find Tests -type f -name '*.php' | sed 's/^Tests\/\(.*\)\.php/\1/');
	do echo;echo $name;echo; ./test $name;
done;
```

***
If you'd like to see other PHP-algorithm comparison in this collection, feel
free to create a new issue. Thanks!

  [License img]: https://img.shields.io/badge/license-BSD3-brightgreen.svg
  [License src]: https://tldrlegal.com/license/bsd-3-clause-license-(revised)
