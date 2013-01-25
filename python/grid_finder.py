def test():
	""" A function to test the whole algo"""
	i = 0
	for xc in range(188000,195000):
		for yc in range(727000,732000):
			xcm = xc/10000.00
			ycm =  yc/10000.00
			grid = int(142.857142*(xcm-18.80)+20*(ycm-72.70))
			print "["+str(i)+"]"+"xc=>"+str(xc)+" yc=>"+str(yc)+" grid=>"+str(grid)
			i += 1

def get_grid(xc,yc):
	grid = int(142.857142*(xcm-18.80)+20*(ycm-72.70))#get all your fancy smazy algos here
	return grid
