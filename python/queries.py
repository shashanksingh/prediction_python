
def get_all_p2p_metadata():
	return "select p2p_metadata as city_taxi_bookings_with_lat_long from ola_bookings where p2p_metadata is not null and service_type='p2p';"

def get_all_p2p_metadata_for_hours(hour):
	return "select p2p_metadata as total_city_taxi_bookings from ola_bookings where service_type='p2p' and year(pickup_date)=2012 and hour(pickup_date)="+hour

def get_all_pickup_drop_lat_lng_timestamp():
	"""instead of reading ola_Bookings table this reads from ola_citytaxi_Status_udapte table which get update from driver app"""
	return 
	"""	select ob.id,
		group_concat(nst.status),
		group_concat(nst.lat) lat,
		group_concat(nst.lng) lng,
		group_concat(nst.timestamp) timestamp
		from
			ola_bookings ob
		left join
			ola_citytaxi_status_update nst ON ob.id = nst.booking_id
		where
		convert_tz(ob.pickup_date, '+0:00', '+5:30') between '2013-1-1 00:00:00' and '2013-1-15 23:59:59'
		and ob.status = 'completed'
		and ob.service_type = 'p2p'
		and ob.service_city = 'Mumbai'
		and ob.deleted = 0
		and nst.status in (5 , 6)
		group by ob.id
		order by nst.status ASC;
	"""
