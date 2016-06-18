#include <ctime>
#include "db.h"

using namespace std;

int main() {
	db myDb;
	myDb.init();
	myDb.setTempFileDir("temp");

	clock_t timex = clock();
	myDb.import("data/2006.csv");
	myDb.import("data/2007.csv");
	myDb.import("data/2008.csv");

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
	printf("Import Time: %.3fs\n", (double) (time0 - timex) / CLOCKS_PER_SEC);
	printf("Index Time: %.3fs\n\n", (double) (time1 - time0) / CLOCKS_PER_SEC);

	printf("##### IAH -> JFK #####\n");
	printf("Average Arrival Delay: %.5f\n", result1);
	printf("Query Time: %.3fs\n\n", (double) (time2 - time1) / CLOCKS_PER_SEC);

	printf("##### IAH -> LAX #####\n");
	printf("Average Arrival Delay: %.5f\n", result2);
	printf("Query Time: %.3fs\n\n", (double) (time3 - time2) / CLOCKS_PER_SEC);

	printf("##### JFK -> LAX #####\n");
	printf("Average Arrival Delay: %.5f\n", result3);
	printf("Query Time: %.3fs\n\n", (double) (time4 - time3) / CLOCKS_PER_SEC);

	printf("##### JFK -> IAH #####\n");
	printf("Average Arrival Delay: %.5f\n", result4);
	printf("Query Time: %.3fs\n\n", (double) (time5 - time4) / CLOCKS_PER_SEC);

	printf("##### LAX -> IAH #####\n");
	printf("Average Arrival Delay: %.5f\n", result5);
	printf("Query Time: %.3fs\n\n", (double) (time6 - time5) / CLOCKS_PER_SEC);

	myDb.cleanup();
	return 0;
}
