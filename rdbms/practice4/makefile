.PHONY: clean
all: clean test
test: main.cpp db.cpp db.h
	g++ -Wall -Wextra -Wpedantic -std=c++11 main.cpp db.cpp -o test
autotest: main_autotest.cpp db_autotest.cpp db_autotest.h
	g++ -Wall -Wextra -Wpedantic -std=c++11 main_autotest.cpp db_autotest.cpp -o autotest
clean:
	rm -f test autotest
