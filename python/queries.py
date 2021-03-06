"""select count(*),date(from_unixtime(timestamp)) as mydate from ola_bookings as ob left join ola_citytaxi_status_update as nst ON ob.id = nst.booking_id  where nst.status = 5 and ob.deleted = "0" and ob.status = 'completed'  and ob.service_type = 'p2p' and ob.service_city = "delhi" and convert_tz(ob.pickup_date, '+0:00', '+5:30') between '2013-1-1 00:00:00' and '2013-1-31 23:59:59' group by mydate """
def get_all_p2p_metadata_for_hours(hour):
	"""read all data for each hour from ola_bookings"""
	return "select p2p_metadata as total_city_taxi_bookings from ola_bookings where service_type='p2p' and year(pickup_date)=2012 and hour(pickup_date)="+hour

def get_all_pickup_drop_lat_lng_timestamp(limit):
	"""instead of reading ola_Bookings table this reads from ola_citytaxi_Status_udapte table which get update from driver app"""
	return """
		select ob.id,
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
		order by nst.status ASC LIMIT 
		"""+str(limit)+""";"""

def get_all_pickup_drop_lat_lng_timestamp_moved_origin(limit):
        """read the function above , also acc. to google 18.9647 and 72.8258 is center of mumbi"""
        return """
                select ob.id,
                group_concat(nst.status),
                group_concat(nst.lat - 18.9647) lat,
                group_concat(nst.lng - 72.8258) lng,
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
                order by nst.status ASC LIMIT 
                """+str(limit)+""";"""

def get_all_pickup_lat_lng_timestamp_moved_origin(limit):
        """read the function above , only gets pickup lat,lngalso acc. to google 18.9647 and 72.8258 is center of mumbi"""
        return """
                select ob.id,
                group_concat(nst.status),
                group_concat(nst.lang - 18.9647) lat,
                group_concat(nst.lng - 72.8258) lng,
                group_concat(nst.timestamp) timestamp
                from
                        ola_bookings ob
                left join
                        ola_citytaxi_status_update nst ON ob.id = nst.booking_id
                where
                convert_tz(ob.pickup_date, '+0:00', '+5:30') between '2012-1-1 00:00:00' and '2013-1-15 23:59:59'
                and ob.status = 'completed'
                and ob.service_type = 'p2p'
                and ob.service_city = 'Mumbai'
                and ob.deleted = 0
                and nst.status = 5
                group by ob.id
                order by nst.status ASC LIMIT 
                """+str(limit)+""";"""

def get_all_pickup_lat_lng_timestamp(limit,service_city):
        """read the function above , only gets pickup lat,lngalso acc. to google 18.9647 and 72.8258 is center of mumbi"""
        return """
                select ob.id,
                group_concat(nst.status),
                group_concat(nst.lat) lat,
                group_concat(nst.lng) lng,
                group_concat(nst.timestamp) timestamp
                from
                        ola_bookings ob
                left join
                        ola_citytaxi_status_update nst ON ob.id = nst.booking_id
                where
                convert_tz(ob.pickup_date, '+0:00', '+5:30') between '2013-1-1 00:00:00' and '2013-1-27 23:59:59'
                and ob.status = 'completed'
                and ob.service_type = 'p2p'
                and ob.service_city = '"""+service_city+"""'
                and ob.deleted = 0
                and nst.status = 5
                group by ob.id
                order by timestamp ASC LIMIT 
                """+str(limit)+""";"""


def get_count_of_bookings_paritioned_by_date():
	"""partition the count of booking date wise"""
	return """select count(name) as "number of bookings" , date(pickup_date) as date from ola_bookings where pickup_date is not null and pickup_date < now() GROUP BY UNIX_TIMESTAMP(pickup_date) DIV 86400 ;"""

def get_count_of_bookings_paritioned_by_date_by_service_city(service_city):
	"""partition the count of bookings date wise and from a service city"""
	return """select count(name) as "number of bookings" , date(pickup_date) as date from ola_bookings where pickup_date is not null and pickup_date < now() and service_city = """.service_city.""" GROUP BY UNIX_TIMESTAMP(pickup_date) DIV 86400 ;"""

