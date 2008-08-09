<?php

	//
	//Class for page layout
	//
	class PageLayout
	{
		//constructor
		function PageLayout()
		{
			//set various page attributes
			$this->mTableWidth = "580";
			$this->mTableBgColour = "#FFFFFF";
			$this->mPageBgColour = "#336699";
			$this->mTableHeaderCellColour = "#DAD3C5";
			$this->mTableCellColour = "#EEEBE3";
			$this->mFontName = "Arial";
			$this->mFontSize = "2";
			$this->mTagFont = "Arial";
			$this->mTagColour = "#00CC66";
			$this->mTagFontSize = "5";
			$this->mJournalNameColour = "#660033";
		}

		//get
		function getTableWidth()
		{
			return $this->mTableWidth;
		}


		function getTableBgColour()
		{
			return $this->mTableBgColour;
		}


		function getPageBgColour()
		{
			return $this->mPageBgColour;
		}

		function getTableHeaderCellColour()
		{
			return $this->mTableHeaderCellColour;
		}

		function getTableCellColour()
		{
			return $this->mTableCellColour;
		}

		function getFontName()
		{
			return $this->mFontName;
		}

		function getLinkColour()
		{
			return $this->mLinkColour;
		}
		function getTagFont()
		{
			return $this->mTagFont;
		}

		function getTagColour()
		{
			return $this->mTagColour;
		}

		function getTagFontSize()
		{
			return $this->mTagFontSize;
		}

		function getJournalNameColour()
		{
			return $this->mJournalNameColour;
		}

		function getFontSize()
		{
			return $this->mFontSize;
		}

		//set

		//is

		//other

		//member variables
		var $mTableWidth;
		var $mTableBgColour;
		var $mPageBgColour;
		var $mTableHeaderCellColour;
		var $mTableCellColour;
		var $mFontName;
		var $mFontColour;
		var $mLinkColour;
		var $mTagFont;
		var $mTagColour;
		var $mTagFontSize;
		var $mJournalNameColour;
		var $mFontSize;


	}


	//
	//class for navigator page layout
	//
	class NavigatorPageLayout
	{
		//constructor
		function NavigatorPageLayout()
		{
			$this->mTableWidth = "130";
			$this->mTableBgColour = "#CCCCB3";
			$this->mPageBgColour = "#DAD3C5";
			$this->mTableCellColour = "#336699";
			$this->mFontName = "Arial";
			$this->mFontColour = "#33CCCC";
			$this->mLinkColour = "#FFFFFF";
		}

		//get
		function getTableWidth()
		{
			return $this->mTableWidth;
		}

		function getTableBgColour()
		{
			return $this->mTableBgColour;
		}

		function getPageBgColour()
		{
			return $this->mPageBgColour;
		}

		function getTableCellColour()
		{
			return $this->mTableCellColour;
		}

		function getFontName()
		{
			return $this->mFontName;
		}

		function getFontColour()
		{
			return $this->mFontColour;
		}

		function getLinkColour()
		{
			return $this->mLinkColour;
		}



		//set

		//is

		//other

		//member variables

		var $mTableWidth;
		var $mTableBgColour;
		var $mPageBgColour;
		var $mTableCellColour;
		var $mFontName;
		var $mFontColour;
		var $mLinkColour;

	}
?>