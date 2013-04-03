from plot import *

def randrange(n, vmin, vmax):
    return (vmax-vmin)*np.random.rand(n) + vmin

def threeD_scatter_plot(data):
	x,y,timestamp = convert_to_numpy_x_y_timestamp(data)
	fig = plt.figure()
	ax = fig.add_subplot(111, projection='3d')
	n = 100
	#for c, m, zl, zh in [('r', 'o', -50, -25), ('b', '^', -30, -5)]:
	#	xs = randrange(n, 23, 32)
	#	ys = randrange(n, 0, 100)
	#	zs = randrange(n, zl, zh)
	ax.scatter(x*10, y*10, 10.00)

	ax.set_xlabel('X Label')
	ax.set_ylabel('Y Label')
	ax.set_zlabel('Z Label')

	plt.show()
