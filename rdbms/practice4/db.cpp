#include <cstdio>
#include <cstdlib>
#include <cstring>
#include <string>
#include "db.h"

using namespace std;

void db::init() {
	isIndexed = false;
	tempFileDir = "temp";
}

void db::setTempFileDir(string dir) {
	tempFileDir = dir;
}

void db::import(string csvDir) {
	string cacheName = tempFileDir + "/cache.bin";
	FILE *cacheFile = fopen(cacheName.c_str(), "wb");

	string csvName = csvDir + "/2006.csv";
	FILE *csvFile = fopen(csvName.c_str(), "r");
	parse(csvFile, cacheFile);
	fclose(csvFile);

	csvName = csvDir + "/2007.csv";
	csvFile = fopen(csvName.c_str(), "r");
	parse(csvFile, cacheFile);
	fclose(csvFile);

	csvName = csvDir + "/2008.csv";
	csvFile = fopen(csvName.c_str(), "r");
	parse(csvFile, cacheFile);
	fclose(csvFile);

	fclose(cacheFile);
}

void db::createIndex() {
	isIndexed = true;
	// Create index.
}

double db::query(string origin, string dest) {
	// Do the query and return the average ArrDelay of flights from origin to dest.
	// This method will be called multiple times.
	return 0; //Remember to return your result.
}

void db::cleanup() {
	// Release memory, close files and anything you should do to clean up your db class.
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
			strtok(NULL, ",");
			char *origin = strtok(NULL, ",");
			char *dest = strtok(NULL, ",");
			fwrite(origin, sizeof(char), 3, fpOut);
			fwrite(dest, sizeof(char), 3, fpOut);
			fwrite(atoi(arrDelay), sizeof(int), 1, fpOut);
		}
	}
	else {
		perror("invalid file pointer");
		abort();
	}
}
