import csv

class file_lib:
	"""store all the data into a file"""

	def __init__(self):
		pass
	def save(self,data):
		try:
			with open('/tmp/file_backend_for_booking_predictor.csv', 'wb') as f:
				writer = csv.writer(f)
				writer.writerows(data)
		except csv.Error as e:
			return False
		return True

	def read(self):
		data = ""
		try:
			with open('/tmp/file_backend_for_booking_predictor.csv', 'rb') as f:
				file_reader = csv.reader(f, delimiter=',', quotechar='|')
				for row in file_reader:
					data += ', '.join(row)
		except csv.Error as e:
			return False

		return data

