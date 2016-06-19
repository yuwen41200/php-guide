#include <cstdio>
#include <string>
#include <unordered_map>
#include <vector>

using namespace std;

class db {

public:
	void init();
	void setTempFileDir(string);
	void import(string);
	void createIndex();
	void query(string, string, int, int);
	void cleanup();

private:
	bool isIndexed;
	string tempFileDir;
	unordered_map<string, vector<long>> index;
	static int testNo;
	static int wrongCount;
	void parse(FILE*, FILE*);
	double bruteForceSearch(FILE*, string);

};
