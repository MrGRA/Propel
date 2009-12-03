<?php

require_once 'tools/helpers/bookstore/BookstoreEmptyTestBase.php';

/**
 * Test class for PropelObjectsFormatter.
 *
 * @author     Francois Zaninotto
 * @version    $Id$
 * @package    runtime.formatter
 */
class PropelObjectsFormatterTest extends BookstoreEmptyTestBase
{
	protected function setUp()
	{
		parent::setUp();
		BookstoreDataPopulator::populate();
	}

	public function testFormatNoCriteria()
	{
		$con = Propel::getConnection(BookPeer::DATABASE_NAME);

		$stmt = $con->query('SELECT * FROM book');
		$formatter = new PropelObjectsFormatter();
		try {
			$books = $formatter->format($stmt);
			$this->fail('PropelObjectsFormatter::format() trows an exception when called with no valid criteria');
		} catch (PropelException $e) {
			$this->assertTrue(true,'PropelObjectsFormatter::format() trows an exception when called with no valid criteria');
		}
	}
	
	public function testFormatManyResults()
	{
		$con = Propel::getConnection(BookPeer::DATABASE_NAME);

		$stmt = $con->query('SELECT * FROM book');
		$formatter = new PropelObjectsFormatter();
		$formatter->setCriteria(new ModelCriteria('bookstore', 'Book'));
		$books = $formatter->format($stmt);
		
		$this->assertTrue(is_array($books), 'PropelObjectsFormatter::format() returns an array');
		$this->assertEquals(4, count($books), 'PropelObjectsFormatter::format() returns as many rows as the results in the query');
		foreach ($books as $book) {
			$this->assertTrue($book instanceof Book, 'PropelObjectsFormatter::format() returns an array of Model objects');
		}
	}

	public function testFormatOneResult()
	{
		$con = Propel::getConnection(BookPeer::DATABASE_NAME);

		$stmt = $con->query('SELECT * FROM book WHERE book.TITLE = "Quicksilver"');
		$formatter = new PropelObjectsFormatter();
		$formatter->setCriteria(new ModelCriteria('bookstore', 'Book'));
		$books = $formatter->format($stmt);
		
		$this->assertTrue(is_array($books), 'PropelObjectsFormatter::format() returns an array');
		$this->assertEquals(1, count($books), 'PropelObjectsFormatter::format() returns as many rows as the results in the query');
		$book = array_shift($books);
		$this->assertTrue($book instanceof Book, 'PropelObjectsFormatter::format() returns an array of Model objects');
		$this->assertEquals('Quicksilver', $book->getTitle(), 'PropelObjectsFormatter::format() returns the model objects matching the query');
	}

	public function testFormatNoResult()
	{
		$con = Propel::getConnection(BookPeer::DATABASE_NAME);
				
		$stmt = $con->query('SELECT * FROM book WHERE book.TITLE = "foo"');
		$formatter = new PropelObjectsFormatter();
		$formatter->setCriteria(new ModelCriteria('bookstore', 'Book'));
		$books = $formatter->format($stmt);
		
		$this->assertTrue(is_array($books), 'PropelObjectsFormatter::format() returns an array');
		$this->assertEquals(0, count($books), 'PropelObjectsFormatter::format() returns as many rows as the results in the query');
	}
	
	public function testFormatOneNoCriteria()
	{
		$con = Propel::getConnection(BookPeer::DATABASE_NAME);

		$stmt = $con->query('SELECT * FROM book');
		$formatter = new PropelObjectsFormatter();
		try {
			$book = $formatter->formatOne($stmt);
			$this->fail('PropelObjectsFormatter::formatOne() throws an exception when called with no valid criteria');
		} catch (PropelException $e) {
			$this->assertTrue(true,'PropelObjectsFormatter::formatOne() throws an exception when called with no valid criteria');
		}
	}
	
	public function testFormatOneManyResults()
	{
		$con = Propel::getConnection(BookPeer::DATABASE_NAME);

		$stmt = $con->query('SELECT * FROM book');
		$formatter = new PropelObjectsFormatter();
		$formatter->setCriteria(new ModelCriteria('bookstore', 'Book'));
		$book = $formatter->formatOne($stmt);
		
		$this->assertTrue($book instanceof Book, 'PropelObjectsFormatter::formatOne() returns a model object');
	}

	public function testFormatOneNoResult()
	{
		$con = Propel::getConnection(BookPeer::DATABASE_NAME);
				
		$stmt = $con->query('SELECT * FROM book WHERE book.TITLE = "foo"');
		$formatter = new PropelObjectsFormatter();
		$formatter->setCriteria(new ModelCriteria('bookstore', 'Book'));
		$book = $formatter->formatOne($stmt);
		
		$this->assertNull($book, 'PropelObjectsFormatter::formatOne() returns null when no result');
	}
	
}
