package com.acertainbookstore.client.tests;

import static org.junit.Assert.*;

import java.util.HashSet;
import java.util.List;
import java.util.Set;

import org.junit.After;
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;

import com.acertainbookstore.business.Book;
import com.acertainbookstore.business.BookCopy;
import com.acertainbookstore.business.BookEditorPick;
import com.acertainbookstore.business.ConcurrentCertainBookStore;
import com.acertainbookstore.business.ImmutableStockBook;
import com.acertainbookstore.business.StockBook;
import com.acertainbookstore.client.BookStoreHTTPProxy;
import com.acertainbookstore.client.StockManagerHTTPProxy;
import com.acertainbookstore.interfaces.BookStore;
import com.acertainbookstore.interfaces.StockManager;
import com.acertainbookstore.utils.BookStoreConstants;
import com.acertainbookstore.utils.BookStoreException;

/**
 * {@link BookStoreTest} tests the {@link BookStore} interface.
 * 
 * @see BookStore
 */
public class BookStoreTest {

	/** The Constant TEST_ISBN. */
	private static final int TEST_ISBN = 3044560;

	/** The Constant NUM_COPIES. */
	private static final int NUM_COPIES = 10;

	/** The local test. */
	private static boolean localTest = true;

	/** The store manager. */
	private static StockManager storeManager;

	/** The client. */
	private static BookStore client;

	/**
	 * Sets the up before class.
	 */
	@BeforeClass
	public static void setUpBeforeClass() {
		try {
			String localTestProperty = System.getProperty(BookStoreConstants.PROPERTY_KEY_LOCAL_TEST);
			localTest = (localTestProperty != null) ? Boolean.parseBoolean(localTestProperty) : localTest;

			if (localTest) {
				ConcurrentCertainBookStore store = new ConcurrentCertainBookStore();
				storeManager = store;
				client = store;
			} else {
				storeManager = new StockManagerHTTPProxy("http://localhost:8081/stock");
				client = new BookStoreHTTPProxy("http://localhost:8081");
			}

			storeManager.removeAllBooks();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/**
	 * Helper method to add some books.
	 *
	 * @param isbn
	 *            the isbn
	 * @param copies
	 *            the copies
	 * @throws BookStoreException
	 *             the book store exception
	 */
	public void addBooks(int isbn, int copies) throws BookStoreException {
		Set<StockBook> booksToAdd = new HashSet<StockBook>();
		StockBook book = new ImmutableStockBook(isbn, "Test of Thrones", "George RR Testin'", (float) 10, copies, 0, 0,
				0, false);
		booksToAdd.add(book);
		storeManager.addBooks(booksToAdd);
	}

	/**
	 * Helper method to get the default book used by initializeBooks.
	 *
	 * @return the default book
	 */
	public StockBook getDefaultBook() {
		return new ImmutableStockBook(TEST_ISBN, "Harry Potter and JUnit", "JK Unit", (float) 10, NUM_COPIES, 0, 0, 0,
				false);
	}

	/**
	 * Method to add a book, executed before every test case is run.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@Before
	public void initializeBooks() throws BookStoreException {
		Set<StockBook> booksToAdd = new HashSet<StockBook>();
		booksToAdd.add(getDefaultBook());
		storeManager.addBooks(booksToAdd);
	}

	/**
	 * Method to clean up the book store, execute after every test case is run.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@After
	public void cleanupBooks() throws BookStoreException {
		storeManager.removeAllBooks();
	}

	/**
	 * Tests basic buyBook() functionality.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@Test
	public void testBuyAllCopiesDefaultBook() throws BookStoreException {
		// Set of books to buy
		Set<BookCopy> booksToBuy = new HashSet<BookCopy>();
		booksToBuy.add(new BookCopy(TEST_ISBN, NUM_COPIES));

		// Try to buy books
		client.buyBooks(booksToBuy);

		List<StockBook> listBooks = storeManager.getBooks();
		assertTrue(listBooks.size() == 1);
		StockBook bookInList = listBooks.get(0);
		StockBook addedBook = getDefaultBook();

		assertTrue(bookInList.getISBN() == addedBook.getISBN() && bookInList.getTitle().equals(addedBook.getTitle())
				&& bookInList.getAuthor().equals(addedBook.getAuthor()) && bookInList.getPrice() == addedBook.getPrice()
				&& bookInList.getNumSaleMisses() == addedBook.getNumSaleMisses()
				&& bookInList.getAverageRating() == addedBook.getAverageRating()
				&& bookInList.getNumTimesRated() == addedBook.getNumTimesRated()
				&& bookInList.getTotalRating() == addedBook.getTotalRating()
				&& bookInList.isEditorPick() == addedBook.isEditorPick());
	}

	/**
	 * Tests that books with invalid ISBNs cannot be bought.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@Test
	public void testBuyInvalidISBN() throws BookStoreException {
		List<StockBook> booksInStorePreTest = storeManager.getBooks();

		// Try to buy a book with invalid ISBN.
		HashSet<BookCopy> booksToBuy = new HashSet<BookCopy>();
		booksToBuy.add(new BookCopy(TEST_ISBN, 1)); // valid
		booksToBuy.add(new BookCopy(-1, 1)); // invalid

		// Try to buy the books.
		try {
			client.buyBooks(booksToBuy);
			fail();
		} catch (BookStoreException ex) {
			;
		}

		List<StockBook> booksInStorePostTest = storeManager.getBooks();

		// Check pre and post state are same.
		assertTrue(booksInStorePreTest.containsAll(booksInStorePostTest)
				&& booksInStorePreTest.size() == booksInStorePostTest.size());
	}

	/**
	 * Tests that books can only be bought if they are in the book store.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@Test
	public void testBuyNonExistingISBN() throws BookStoreException {
		List<StockBook> booksInStorePreTest = storeManager.getBooks();

		// Try to buy a book with ISBN which does not exist.
		HashSet<BookCopy> booksToBuy = new HashSet<BookCopy>();
		booksToBuy.add(new BookCopy(TEST_ISBN, 1)); // valid
		booksToBuy.add(new BookCopy(100000, 10)); // invalid

		// Try to buy the books.
		try {
			client.buyBooks(booksToBuy);
			fail();
		} catch (BookStoreException ex) {
			;
		}

		List<StockBook> booksInStorePostTest = storeManager.getBooks();

		// Check pre and post state are same.
		assertTrue(booksInStorePreTest.containsAll(booksInStorePostTest)
				&& booksInStorePreTest.size() == booksInStorePostTest.size());
	}

	/**
	 * Tests that you can't buy more books than there are copies.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@Test
	public void testBuyTooManyBooks() throws BookStoreException {
		List<StockBook> booksInStorePreTest = storeManager.getBooks();

		// Try to buy more copies than there are in store.
		HashSet<BookCopy> booksToBuy = new HashSet<BookCopy>();
		booksToBuy.add(new BookCopy(TEST_ISBN, NUM_COPIES + 1));

		try {
			client.buyBooks(booksToBuy);
			fail();
		} catch (BookStoreException ex) {
			;
		}

		List<StockBook> booksInStorePostTest = storeManager.getBooks();
		assertTrue(booksInStorePreTest.containsAll(booksInStorePostTest)
				&& booksInStorePreTest.size() == booksInStorePostTest.size());
	}

	/**
	 * Tests that you can't buy a negative number of books.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@Test
	public void testBuyNegativeNumberOfBookCopies() throws BookStoreException {
		List<StockBook> booksInStorePreTest = storeManager.getBooks();

		// Try to buy a negative number of copies.
		HashSet<BookCopy> booksToBuy = new HashSet<BookCopy>();
		booksToBuy.add(new BookCopy(TEST_ISBN, -1));

		try {
			client.buyBooks(booksToBuy);
			fail();
		} catch (BookStoreException ex) {
			;
		}

		List<StockBook> booksInStorePostTest = storeManager.getBooks();
		assertTrue(booksInStorePreTest.containsAll(booksInStorePostTest)
				&& booksInStorePreTest.size() == booksInStorePostTest.size());
	}

	/**
	 * Tests that all books can be retrieved.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@Test
	public void testGetBooks() throws BookStoreException {
		Set<StockBook> booksAdded = new HashSet<StockBook>();
		booksAdded.add(getDefaultBook());

		Set<StockBook> booksToAdd = new HashSet<StockBook>();
		booksToAdd.add(new ImmutableStockBook(TEST_ISBN + 1, "The Art of Computer Programming", "Donald Knuth",
				(float) 300, NUM_COPIES, 0, 0, 0, false));
		booksToAdd.add(new ImmutableStockBook(TEST_ISBN + 2, "The C Programming Language",
				"Dennis Ritchie and Brian Kerninghan", (float) 50, NUM_COPIES, 0, 0, 0, false));

		booksAdded.addAll(booksToAdd);

		storeManager.addBooks(booksToAdd);

		// Get books in store.
		List<StockBook> listBooks = storeManager.getBooks();

		// Make sure the lists equal each other.
		assertTrue(listBooks.containsAll(booksAdded) && listBooks.size() == booksAdded.size());
	}

	/**
	 * Tests that a list of books with a certain feature can be retrieved.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@Test
	public void testGetCertainBooks() throws BookStoreException {
		Set<StockBook> booksToAdd = new HashSet<StockBook>();
		booksToAdd.add(new ImmutableStockBook(TEST_ISBN + 1, "The Art of Computer Programming", "Donald Knuth",
				(float) 300, NUM_COPIES, 0, 0, 0, false));
		booksToAdd.add(new ImmutableStockBook(TEST_ISBN + 2, "The C Programming Language",
				"Dennis Ritchie and Brian Kerninghan", (float) 50, NUM_COPIES, 0, 0, 0, false));

		storeManager.addBooks(booksToAdd);

		// Get a list of ISBNs to retrieved.
		Set<Integer> isbnList = new HashSet<Integer>();
		isbnList.add(TEST_ISBN + 1);
		isbnList.add(TEST_ISBN + 2);

		// Get books with that ISBN.
		List<Book> books = client.getBooks(isbnList);

		// Make sure the lists equal each other
		assertTrue(books.containsAll(booksToAdd) && books.size() == booksToAdd.size());
	}

	/**
	 * Tests that books cannot be retrieved if ISBN is invalid.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@Test
	public void testGetInvalidIsbn() throws BookStoreException {
		List<StockBook> booksInStorePreTest = storeManager.getBooks();

		// Make an invalid ISBN.
		HashSet<Integer> isbnList = new HashSet<Integer>();
		isbnList.add(TEST_ISBN); // valid
		isbnList.add(-1); // invalid

		HashSet<BookCopy> booksToBuy = new HashSet<BookCopy>();
		booksToBuy.add(new BookCopy(TEST_ISBN, -1));

		try {
			client.getBooks(isbnList);
			fail();
		} catch (BookStoreException ex) {
			;
		}

		List<StockBook> booksInStorePostTest = storeManager.getBooks();
		assertTrue(booksInStorePreTest.containsAll(booksInStorePostTest)
				&& booksInStorePreTest.size() == booksInStorePostTest.size());
	}

	/*
	 * Concurrency Test 2
	 */
	@Test
	public void test2_concurency() throws BookStoreException {
		Set<StockBook> booksToAdd = new HashSet<StockBook>();
		booksToAdd.add(new ImmutableStockBook(TEST_ISBN + 666, "The Art of Computer Programming", "Donald Knuth",
				(float) 300, NUM_COPIES, 0, 0, 0, false));
		storeManager.addBooks(booksToAdd);

		Thread thread_client = new Thread(new Runnable() {
			@Override
			public void run() {
				int i = 100;

				try {
					List<StockBook> listBooks = storeManager.getBooks();
					while (i > 0) {

						HashSet<BookCopy> booksToBuy = new HashSet<BookCopy>();
						booksToBuy.add(new BookCopy(TEST_ISBN + 666, 1)); // valid

						for (StockBook book : listBooks) {
							if (book.getISBN() == TEST_ISBN + 666) {
								client.buyBooks(booksToBuy);
								HashSet<BookCopy> booksToAdd = new HashSet<BookCopy>();
								int copies = book.getNumCopies() - 1;
								booksToAdd.add(new BookCopy(TEST_ISBN + 666, copies));
								storeManager.addCopies(booksToAdd);

								copies++;
								HashSet<BookCopy> booksToReplenish = new HashSet<BookCopy>();
								booksToReplenish.add(new BookCopy(TEST_ISBN + 666, copies));
								storeManager.addCopies(booksToReplenish);
							}
						}
					}
				} catch (BookStoreException e1) {
					System.out.println("Failed test 2");
				}
			}
			// }
		});

		Thread thread_manager = new Thread(new Runnable() {
			@Override
			public void run() {
				int i = 100;
				int copies = 0;
				try {
					List<StockBook> listBooks = storeManager.getBooks();
					for (StockBook book : listBooks) {
						if (book.getISBN() == TEST_ISBN + 666) {
							copies = book.getNumCopies();
						}
					}
					List<StockBook> listBooksTest = storeManager.getBooks();
					while (i > 0) {

						for (StockBook book : listBooksTest) {
							if (book.getISBN() == TEST_ISBN + 666) {
								assertTrue(book.getNumCopies() == copies || book.getNumCopies() == (copies - 1));
							}
						}
					}
				} catch (BookStoreException e1) {
					System.out.println("Failed test 2");
				}

			}
			// }
		});

		thread_client.start();
		thread_manager.start();

	}

	/*
	 * Concurrency test 1
	 */
	@Test
	public void test1_concurency() throws BookStoreException, InterruptedException {
		addBooks(TEST_ISBN+1,5);
		List<StockBook> booksInStorePreTest = storeManager.getBooks();

		Thread thread_client = new Thread(new Runnable() {

			@Override
			public void run() {
				
				try {
					System.out.println("C1");
					int i = 5;
					while (i > 0) {
						Set<BookCopy> booksToBuy = new HashSet<BookCopy>();
						booksToBuy.add(new BookCopy(TEST_ISBN + 1, 1));

						client.buyBooks(booksToBuy);

						i--;
					}
				} catch (BookStoreException e1) {
					System.out.println("Concurrency test 1 Fail");
					e1.getStackTrace();
				}
			}

		});

		Thread thread_manager = new Thread(new Runnable() {
			@Override
			public void run() {
				System.out.println("C2");
				int i = 5;
				try {

					while (i > 0) {
						List<StockBook> listBooks = storeManager.getBooks();
						for (StockBook book : listBooks) {
							if (book.getISBN() == (TEST_ISBN + 1)) {
								HashSet<BookCopy> booksToAdd = new HashSet<BookCopy>();
								booksToAdd.add(new BookCopy(TEST_ISBN + 1, 1));
								storeManager.addCopies(booksToAdd);
							}
						}
						i--;
					}
				} catch (BookStoreException e1) {
					System.out.println("Concurrency test 1 fail C2");
				}

			}
		});

		thread_manager.start();
		thread_client.start();

		List<StockBook> booksInStorePostTest = storeManager.getBooks();
		assertTrue(booksInStorePreTest.containsAll(booksInStorePostTest)
				&& booksInStorePreTest.size() == booksInStorePostTest.size());
		for (StockBook i : booksInStorePostTest) {
			for (StockBook j : booksInStorePreTest) {
				if (i.getISBN() == j.getISBN()) {
					assertTrue(i.getNumCopies() == j.getNumCopies());
				}
			}
		}
	}

	/*
	 * Concurrent test 3
	 */
	@Test
	public void test3_concurency() throws BookStoreException {
		List<StockBook> booksInStorePreTest = storeManager.getBooks();
		Set<StockBook> booksToAdd = new HashSet<StockBook>();
		booksToAdd.add(new ImmutableStockBook(TEST_ISBN + 555, "The Art of Computer Programming", "Donald Knuth",
				(float) 300, NUM_COPIES, 0, 0, 0, false));
		storeManager.addBooks(booksToAdd);

		Thread thread_manager1 = new Thread(new Runnable() {
			@Override
			public void run() {

				try {
					List<StockBook> listBooks = storeManager.getBooks();
					for (StockBook i : listBooks) {
						if (i.getISBN() == TEST_ISBN + 555) {
							boolean is = i.isEditorPick();
							Set<BookEditorPick> editorPicks = new HashSet<BookEditorPick>();
							editorPicks.add(new BookEditorPick(TEST_ISBN + 555, !is));
							storeManager.updateEditorPicks(editorPicks);
						}
					}

				} catch (BookStoreException e1) {
					System.out.println("Concurrency test 3 fail");
				}

			}

		});

		Thread thread_manager2 = new Thread(new Runnable() {
			@Override
			public void run() {

				try {
					List<StockBook> listBooks = storeManager.getBooks();
					for (StockBook i : listBooks) {
						if (i.getISBN() == TEST_ISBN + 555) {
							boolean is = i.isEditorPick();
							Set<BookEditorPick> editorPicks = new HashSet<BookEditorPick>();
							editorPicks.add(new BookEditorPick(TEST_ISBN + 555, !is));
							storeManager.updateEditorPicks(editorPicks);
						}
					}

				} catch (BookStoreException e1) {
					System.out.println("Concurrency Test 3 fail");
				}

			}

		});

		thread_manager1.start();
		thread_manager2.start();

		List<StockBook> booksInStorePostTest = storeManager.getBooks();
		// Check pre and post state are same.
		for (StockBook i : booksInStorePostTest) {
			for (StockBook j : booksInStorePreTest) {
				if (i.getISBN() == TEST_ISBN + 555 && TEST_ISBN + 555 == j.getISBN()) {
					assertTrue(i.isEditorPick() == j.isEditorPick());
				}
			}
		}

	}

	/*
	 * Concurrent test 4
	 * 
	 */
	@Test
	public void test4_concurency() throws BookStoreException {
		List<StockBook> booksInStorePreTest = storeManager.getBooks();
		int preCopies = 0;
		for (StockBook i : booksInStorePreTest) {
			preCopies += i.getNumCopies();
		}

		Thread thread_client = new Thread(new Runnable() {
			@Override
			public void run() {

				try {
					HashSet<StockBook> booksToAdd = new HashSet<StockBook>();
					booksToAdd.add(new ImmutableStockBook(TEST_ISBN + 444, "The Art of Computer Programming",
							"Donald Knuth", (float) 300, NUM_COPIES, 0, 0, 0, false));
					storeManager.addBooks(booksToAdd);
				} catch (BookStoreException e1) {

					System.out.println("failed test 4");
				}

			}

		});

		Thread thread_manager2 = new Thread(new Runnable() {
			@Override
			public void run() {

				try {
					List<StockBook> listBooks = storeManager.getBooks();
					for (StockBook i : listBooks) {
						if (i.getISBN() == TEST_ISBN + 444) {

							Set<Integer> isbnSet = new HashSet<Integer>();
							isbnSet.add(TEST_ISBN + 444);
							storeManager.removeBooks(isbnSet);
						}
					}

				} catch (BookStoreException e1) {
					System.out.println("Concurrency test 4 fail");
				}

			}

		});

		thread_client.start();
		thread_manager2.start();

		List<StockBook> booksInStoreAfterTest = storeManager.getBooks();
		int postCopies = 0;
		for (StockBook i : booksInStoreAfterTest) {
			postCopies += i.getNumCopies();
		}

		assertTrue(preCopies == postCopies);
	}

	/**
	 * Tear down after class.
	 *
	 * @throws BookStoreException
	 *             the book store exception
	 */
	@AfterClass
	public static void tearDownAfterClass() throws BookStoreException {
		storeManager.removeAllBooks();

		if (!localTest) {
			((BookStoreHTTPProxy) client).stop();
			((StockManagerHTTPProxy) storeManager).stop();
		}
	}
}
