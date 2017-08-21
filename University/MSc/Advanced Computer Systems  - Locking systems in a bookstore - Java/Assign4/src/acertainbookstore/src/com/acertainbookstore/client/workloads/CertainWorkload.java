/**
 * 
 */
package com.acertainbookstore.client.workloads;

import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

import com.acertainbookstore.business.CertainBookStore;
import com.acertainbookstore.business.ImmutableStockBook;
import com.acertainbookstore.business.StockBook;
import com.acertainbookstore.client.BookStoreHTTPProxy;
import com.acertainbookstore.client.StockManagerHTTPProxy;
import com.acertainbookstore.interfaces.BookStore;
import com.acertainbookstore.interfaces.StockManager;
import com.acertainbookstore.utils.BookStoreConstants;
import com.acertainbookstore.utils.BookStoreException;

/**
 * 
 * CertainWorkload class runs the workloads by different workers concurrently.
 * It configures the environment for the workers using WorkloadConfiguration
 * objects and reports the metrics
 * 
 */
public class CertainWorkload {

	/**
	 * @param args
	 */
	public static void main(String[] args) throws Exception {
		int numConcurrentWorkloadThreads = 10;
		String serverAddress = "http://localhost:8081";
		boolean localTest = true;
		List<WorkerRunResult> workerRunResults = new ArrayList<WorkerRunResult>();
		List<Future<WorkerRunResult>> runResults = new ArrayList<Future<WorkerRunResult>>();

		// Initialize the RPC interfaces if its not a localTest, the variable is
		// overriden if the property is set
		String localTestProperty = System
				.getProperty(BookStoreConstants.PROPERTY_KEY_LOCAL_TEST);
		localTest = (localTestProperty != null) ? Boolean
				.parseBoolean(localTestProperty) : localTest;

		BookStore bookStore = null;
		StockManager stockManager = null;
		if (localTest) {
			CertainBookStore store = new CertainBookStore();
			bookStore = store;
			stockManager = store;
		} else {
			stockManager = new StockManagerHTTPProxy(serverAddress + "/stock");
			bookStore = new BookStoreHTTPProxy(serverAddress);
		}

		// Generate data in the bookstore before running the workload
		initializeBookStoreData(bookStore, stockManager);

		ExecutorService exec = Executors
				.newFixedThreadPool(numConcurrentWorkloadThreads);

		for (int i = 0; i < numConcurrentWorkloadThreads; i++) {
			WorkloadConfiguration config = new WorkloadConfiguration(bookStore,
					stockManager);
			Worker workerTask = new Worker(config);
			// Keep the futures to wait for the result from the thread
			runResults.add(exec.submit(workerTask));
		}

		// Get the results from the threads using the futures returned
		for (Future<WorkerRunResult> futureRunResult : runResults) {
			WorkerRunResult runResult = futureRunResult.get(); // blocking call
			workerRunResults.add(runResult);
		}

		exec.shutdownNow(); // shutdown the executor

		// Finished initialization, stop the clients if not localTest
		if (!localTest) {
			((BookStoreHTTPProxy) bookStore).stop();
			((StockManagerHTTPProxy) stockManager).stop();
		}

		reportMetric(workerRunResults);
	}

	/**
	 * Computes the metrics and prints them
	 * 
	 * @param workerRunResults
	 */
	public static void reportMetric(List<WorkerRunResult> workerRunResults) {
		double clientGP = 0;
		double  latencyAv = 0;
		double percentageThrough=0;
		double throughput =0;
		double goodPut= 0;
		double client = 0;
		double totalRun =0;
		for(WorkerRunResult a: workerRunResults){
			clientGP += (double) a.getSuccessfulFrequentBookStoreInteractionRuns() / (a.getElapsedTimeInNanoSecs()/ 1000000);
			goodPut += (double) a.getSuccessfulInteractions()/((double)a.getElapsedTimeInNanoSecs()/ 1000000);
			latencyAv += (double)a.getElapsedTimeInNanoSecs() / 1000000.0;
			throughput += (double) a.getTotalRuns() / ((double)a.getElapsedTimeInNanoSecs()/ 1000000);
			client += (double) a.getTotalFrequentBookStoreInteractionRuns()/(double)a.getTotalRuns();
			totalRun += a.getTotalRuns();
		}
		percentageThrough = ((goodPut/throughput));
		
		client = client /workerRunResults.size();
		System.out.println("Number of Runs "+  totalRun );
		System.out.println("Client perc "+ client);
		latencyAv = latencyAv/workerRunResults.size();
		System.out.println("Percentage through " + percentageThrough);
		System.out.println("Agregate of client goodput "+ clientGP);
		System.out.println("average latency " + latencyAv + " milliseconds" );
		
		
	}

	/**
	 * Generate the data in bookstore before the workload interactions are run
	 * 
	 * Ignores the serverAddress if its a localTest
	 * 
	 */
	public static void initializeBookStoreData(BookStore bookStore,
			StockManager stockManager) throws BookStoreException {
		
		
		HashSet <StockBook> sample = new HashSet <StockBook> ();
		int i = sample.size();
		while(i < 12){
			int isbnR = 1000+i;
			int numCopies = 1+i;
			float priceR = 100 + i;
			String finalTitle = "Harry Potter " + i;
			String finalAuthor = "J.K. Rowling" + i;
			long numSaleMisses = 0;
			long numTimesRated = 1;
			long totalRating = 5;
			boolean editorPick = false;
			ImmutableStockBook stockBook = new ImmutableStockBook (isbnR,finalTitle,finalAuthor,priceR,numCopies,numSaleMisses,numTimesRated,totalRating,editorPick);
			sample.add(stockBook);
			i++;
		}
		
		stockManager.addBooks(sample);
		
	}
}
