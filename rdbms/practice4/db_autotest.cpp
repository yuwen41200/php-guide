#include <cstdlib>
#include <cstring>
#include "db_autotest.h"

using namespace std;

int db::testNo = 0;
int db::wrongCount = 0;

void db::init() {
	isIndexed = false;
	tempFileDir = "temp";
}

void db::setTempFileDir(string dir) {
	tempFileDir = dir;
	string cacheName = tempFileDir + "/cache.bin";
	remove(cacheName.c_str()); // clear old data if exists
}

void db::import(string csvName) {
	string cacheName = tempFileDir + "/cache.bin";
	FILE *cacheFile = fopen(cacheName.c_str(), "ab");
	FILE *csvFile = fopen(csvName.c_str(), "r");

	parse(csvFile, cacheFile);

	fclose(csvFile);
	fclose(cacheFile);
}

void db::createIndex() {
	char key[7] = "";
	long value;

	string cacheName = tempFileDir + "/cache.bin";
	FILE *cacheFile = fopen(cacheName.c_str(), "rb");
	if (!cacheFile) {
		perror("db::createIndex() file open failed");
		abort();
	}

	while (true) {
		fread(key, sizeof(char), 6, cacheFile);
		if (feof(cacheFile))
			break;
		value = ftell(cacheFile);
		index[key].push_back(value);
		fseek(cacheFile, sizeof(int), SEEK_CUR);
	}

	fclose(cacheFile);
	isIndexed = true;
}

void db::query(string origin, string dest, int correctSum, int count) {
	string search = origin + dest;
	int found = 0, sum = 0;

	string cacheName = tempFileDir + "/cache.bin";
	FILE *cacheFile = fopen(cacheName.c_str(), "rb");
	if (!cacheFile) {
		perror("db::query() file open failed");
		abort();
	}

	if (!isIndexed) {
		perror("impossible in autotest");
		abort();
	}

	for (auto const &pos : index[search]) {
		fseek(cacheFile, pos, SEEK_SET);
		fread(&found, sizeof(int), 1, cacheFile);
		sum += found;
	}

	fclose(cacheFile);
	if (correctSum == sum && count == (int) index[search].size())
		printf("%4d/5711 correct\n", ++testNo);
	else {
		printf("%4d/5711 wrong\n", ++testNo);
		printf("answer: sum = %d, count = %d\n", correctSum, count);
		printf("yours: sum = %d, count = %lu\n", sum, index[search].size());
		++wrongCount;
	}
}

void db::cleanup() {
	printf("wrong answer: %d\n", wrongCount);
	if (isIndexed) {
		index.clear();
		isIndexed = false;
	}
}

void db::parse(FILE *fpIn, FILE *fpOut) {
	if (fpIn && fpOut) {
		char row[310];
		fgets(row, 310, fpIn); // ignore the first line
		while (fgets(row, 310, fpIn)) {
			strtok(row, ","); // ignore unnecessary columns
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			strtok(NULL, ",");
			char *arrDelay = strtok(NULL, ",");
			int intArrDelay = atoi(arrDelay);
			if (intArrDelay == 0 && strchr(arrDelay, '0') == NULL) {
				if (strcmp(arrDelay, "NA"))
					printf("\"%s\" ", arrDelay); // if it is not N/A, what is it?
				continue; // arrDelay is empty or N/A
			}
			strtok(NULL, ",");
			char *origin = strtok(NULL, ",");
			if (strlen(origin) != 3)
				printf("\"%s\" ", origin); // if its length is not 3, what is it?
			char *dest = strtok(NULL, ",");
			if (strlen(dest) != 3)
				printf("\"%s\" ", dest); // if its length is not 3, what is it?
			fwrite(origin, sizeof(char), 3, fpOut);
			fwrite(dest, sizeof(char), 3, fpOut);
			fwrite(&intArrDelay, sizeof(int), 1, fpOut);
		}
	}
	else {
		perror("db::parse() file open failed");
		abort();
	}
}

double db::bruteForceSearch(FILE* fpIn, string str) {
	char key[7] = "";
	int value = 0, sum = 0, count = 0;
	while (true) {
		fread(key, sizeof(char), 6, fpIn);
		if (feof(fpIn))
			break;
		if (str == key) {
			fread(&value, sizeof(int), 1, fpIn);
			sum += value;
			++count;
		}
		else
			fseek(fpIn, sizeof(int), SEEK_CUR);
	}
	fclose(fpIn);
	return (double) sum / count;
}
