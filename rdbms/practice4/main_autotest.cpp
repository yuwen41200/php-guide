#include <cstdlib>
#include <cstring>
#include "db_autotest.h"

using namespace std;

int main() {
	db myDb;
	myDb.init();
	myDb.setTempFileDir("temp");

	myDb.import("data/2006.csv");
	myDb.import("data/2007.csv");
	myDb.import("data/2008.csv");
	myDb.createIndex();

	FILE *testData = fopen("ans.csv", "r");
	if (!testData) {
		perror("main() file open failed");
		abort();
	}

	char row[35];
	while (fgets(row, 35, testData)) {
		char *origin = strtok(row, ",");
		char *dest = strtok(NULL, ",");
		char *sum = strtok(NULL, ",");
		char *count = strtok(NULL, ",");
		int intSum = atoi(sum);
		int intCount = atoi(count);
		myDb.query(origin, dest, intSum, intCount);
	}

	fclose(testData);
	myDb.cleanup();
	return 0;
}
