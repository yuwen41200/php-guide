#include <cstdlib>
#include <cstring>
#include "db.h"

using namespace std;

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

double db::query(string origin, string dest) {
	string search = origin + dest;
	int found = 0, sum = 0;

	string cacheName = tempFileDir + "/cache.bin";
	FILE *cacheFile = fopen(cacheName.c_str(), "rb");
	if (!cacheFile) {
		perror("db::query() file open failed");
		abort();
	}

	if (!isIndexed)
		return bruteForceSearch(cacheFile, search);

	for (auto const &pos : index[search]) {
		fseek(cacheFile, pos, SEEK_SET);
		fread(&found, sizeof(int), 1, cacheFile);
		sum += found;
	}

	fclose(cacheFile);
	#ifdef DEBUG
	printf("DEBUG: sum = %d, count = %lu\n", sum, index[search].size());
	#endif
	return (double) sum / index[search].size();
}

void db::cleanup() {
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
			if (intArrDelay == 0 && strchr(arrDelay, '0') == NULL)
				continue; // arrDelay is empty or N/A
			strtok(NULL, ",");
			char *origin = strtok(NULL, ",");
			char *dest = strtok(NULL, ",");
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
	#ifdef DEBUG
	printf("DEBUG: sum = %d, count = %d\n", sum, count);
	#endif
	return (double) sum / count;
}
