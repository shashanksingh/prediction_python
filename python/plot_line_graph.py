from plot import *
from pylab import *
def simple_line_plot(x,y):
	grid(True)
	plt.plot(x,y)
	xlabel('Time (s since jan 1 1970)')
	ylabel('Location of Hotspot of pickup')
        figure = plt.gcf()
        figure.set_size_inches(36, 12)
	savefig("simple_line_plot.png",dpi=50)
	show()


def line_plot_with_histogram(x,y):
	grid(True)
	hist(x,y)
	savefig("line_plot_with_histogram.png")

def curve_fitting_plot(x,y):
	from scipy.interpolate import spline
	xnew = np.linspace(np.array(x).min(),np.array(x).max(),50)
	y_smooth = spline(x,y,xnew)
	plt.plot(xnew,y_smooth)
	plt.savefig("curve_fitting_plot.png")
	
