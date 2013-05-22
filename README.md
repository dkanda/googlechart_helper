googlechart_helper
==================

How to use it:

$title="This is my chart"; //this is the title!
$head=array( //this array describe the columns, this will be the xaxis and the yaxis label
"column1",
"column2",
"column3",
"column4",
"column5" );

$arraydata=array(
  array("row0data1","data2","data3","data4","data5"),
  array("row1data1","data2","data3","data4","data5"),
  array("row2data1","data2","data3","data4","data5"),
  array("row3data1","data2","data3","data4","data5"),
  array("row4data1","data2","data3","data4","data5")
);

$xdata="column3"; //this will be used for the X axis.

Line chart
echo plot_line($title,$head,$arraydata,$xdata);

horizontal barchart

echo plot_bar($title,$head,$arraydata,$xdata);

vertical barchart

echo plot_vertical($title,$head,$arraydata,$xdata);
