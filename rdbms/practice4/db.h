#include <cstdio>
#include <string>

using namespace std;

class db {

public:
	void init();
	void setTempFileDir(std::string);
	void import(std::string);
	void createIndex();
	double query(std::string, std::string);
	void cleanup();

private:
	string tempFileDir;
	bool isIndexed;
	void parse(FILE*, FILE*);

};
