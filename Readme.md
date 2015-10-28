kaystrobach/cldr
=====

This `typo3/flow` package contains basic functionality to access CLDR data, which is shipped with __flow__.

* http://cldr.unicode.org/

Flow ships a lowlevel API to access the data. The problem with that approach is, that this way often huge
XML files are parsed which causes an huge impact on speed.

To fix that this package uses the caching mechanism shipped with __flow__ and provides entities and value objects for
langauges and soon also other cldr data like territories, currencies, etc.

