
def get_all_p2p_metadata():
	return "select p2p_metadata as city_taxi_bookings_with_lat_long from ola_bookings where p2p_metadata is not null and service_type='p2p';"

def get_all_p2p_metadata_for_hours(hour):
	return "select p2p_metadata as total_city_taxi_bookings from ola_bookings where service_type='p2p' and year(pickup_date)=2012 and hour(pickup_date)="+hour
