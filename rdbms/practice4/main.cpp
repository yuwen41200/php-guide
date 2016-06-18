#include <ctime>
#include <iostream>
#include "db.h"

using namespace std;

int main(int argc, char** argv) {
	db myDb;
	myDb.init();
	myDb.setTempFileDir("temp");
	myDb.import("data");

	clock_t time0 = clock();
	myDb.createIndex();

	clock_t time1 = clock();
	double result1 = myDb.query("IAH", "JFK");
	clock_t time2 = clock();
	double result2 = myDb.query("IAH", "LAX");
	clock_t time3 = clock();
	double result3 = myDb.query("JFK", "LAX");
	clock_t time4 = clock();
	double result4 = myDb.query("JFK", "IAH");
	clock_t time5 = clock();
	double result5 = myDb.query("LAX", "IAH");

	clock_t time6 = clock();
	printf("Index Time: %.2fs\n\n", (double) (time1 - time0) / CLOCKS_PER_SEC);

	printf("##### IAH -> JFK #####\n");
	printf("Average Arrival Delay: %.2f\n", result1);
	printf("Query Time: %.2fs\n\n", (double) (time2 - time1) / CLOCKS_PER_SEC);

	printf("##### IAH -> LAX #####\n");
	printf("Average Arrival Delay: %.2f\n", result2);
	printf("Query Time: %.2fs\n\n", (double) (time3 - time2) / CLOCKS_PER_SEC);

	printf("##### JFK -> LAX #####\n");
	printf("Average Arrival Delay: %.2f\n", result3);
	printf("Query Time: %.2fs\n\n", (double) (time4 - time3) / CLOCKS_PER_SEC);

	printf("##### JFK -> IAH #####\n");
	printf("Average Arrival Delay: %.2f\n", result4);
	printf("Query Time: %.2fs\n\n", (double) (time5 - time4) / CLOCKS_PER_SEC);

	printf("##### LAX -> IAH #####\n");
	printf("Average Arrival Delay: %.2f\n", result5);
	printf("Query Time: %.2fs\n\n", (double) (time6 - time5) / CLOCKS_PER_SEC);

	myDb.cleanup();
	return 0;
}
