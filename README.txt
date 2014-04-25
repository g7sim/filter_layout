Layout Filter
=============
Copyright 2014 Richard Oelmann - released as GPLv3 as a Moodle plugin

This filter begins to allow the bootstrap grids to be used in the Moodle text editor without the need to go into the html code.
It is an effort to provide an alternative to using tables for layout purposes for users who are not familiar with html.

It attempts to cater for both Bootstrap 2 and 3 by adding both sets of classes to the div created - as they are different, bootstrap 2 themes should ignore the bootstrap3 classes and visa-versa. However, only simple structures are catered for in Bootstrap 3 using the box key (see below)

To use the filter some minimal knowledge of how html is structured is ie - the fact that each block you create (each div) has to be closed, but they can be nested.
e.g.
[layout-row]
    [layout-box-4]Some content here[layout-end]
    [layout-box-6]Some more content, an image, or whatever you may want to add[layout-end]
    [layout-box-2]3rd narrow column[layout-end]
[layout-end]

The key:
========
[layout-row] - starts a new row
[layout-box-x] - starts a grid with col-md-x (BS3) and spanx (BS2) classes. NOTE: BS grids should add up to 12 in any row
[layout-end] - closes a div
[layout-div-abcxyz] - creates a div with the class abcxyz (or whatever is added there) This can be used to add just about any class, including the more complex BS3 grid classes, with offsets and so on. It is anticipated though that anyone with this level of knowledge is probably happy enough to go into the html anyway!
