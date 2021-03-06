from plot import *

def scatter_plot(x,y,timestamp):
	nullfmt   = NullFormatter()         # no labels

	# definitions for the axes
	left, width = 0.1, 0.65
	bottom, height = 0.1, 0.65
	bottom_h = left_h = left+width+0.02

	rect_scatter = [left, bottom, width, height]
	rect_histx = [left, bottom_h, width, 0.2]
	rect_histy = [left_h, bottom, 0.2, height]

	# start with a rectangular Figure
	plt.figure(1, figsize=(8,8))

	axScatter = plt.axes(rect_scatter)
	axHistx = plt.axes(rect_histx)
	axHisty = plt.axes(rect_histy)

	# no labels
	axHistx.xaxis.set_major_formatter(nullfmt)
	axHisty.yaxis.set_major_formatter(nullfmt)

	# the scatter plot:
	axScatter.scatter(x, y, marker="o", c="green", facecolors="white", edgecolors="red")
	#axScatter.scatter(x_split[1], y_split[0], marker="o", c="green", facecolors="white", edgecolors="green")
	#axScatter.plot(x, y, 'rs--', label='line 1', linewidth=2)

	# now determine nice limits by hand:
	binwidth = 0.25
	xymax = np.max( [np.max(np.fabs(x)), np.max(np.fabs(y))] )
	lim = ( int(xymax/binwidth) + 1) * binwidth

	axScatter.set_xlim( (-lim, lim) )
	axScatter.set_ylim( (-lim, lim) )

	bins = np.arange(-lim, lim + binwidth, binwidth)
	axHistx.hist(x, bins=bins)
	axHisty.hist(y, bins=bins, orientation='horizontal')

	axHistx.set_xlim( axScatter.get_xlim() )
	axHisty.set_ylim( axScatter.get_ylim() )
	
	axScatter.text(18.9300,72.8200,"ChurchGate",bbox=dict(facecolor='green', alpha=0.5))
	#axScatter.text(19.1833,72.8333,"Malad",bbox=dict(facecolor='green', alpha=0.5))
	axScatter.text(19.0587,72.8997,"Chembur",bbox=dict(facecolor='green', alpha=0.5))
	axScatter.text(19.2045,72.8376,"Kandivili",bbox=dict(facecolor='green', alpha=0.5))

	plt.show()

