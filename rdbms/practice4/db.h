#include <string>

class db {

public:
	void init();
	void setTempFileDir(std::string);
	void import(std::string);
	void createIndex();
	double query(std::string, std::string);
	void cleanup();

};
