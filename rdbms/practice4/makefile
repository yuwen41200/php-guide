.PHONY: clean
all: clean test
test: main.cpp db.cpp db.h
	g++ -Wall -Wextra -Wpedantic -std=c++11 main.cpp db.cpp -o test
clean:
	rm test
