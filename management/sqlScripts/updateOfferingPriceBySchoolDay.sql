update offerings

set price='6270'

where refId in 
	(select refId from (
		(select o.refId
		from offerings as o
		left join offeringSchoolNodes as osn on osn.offeringRefId=o.refId
		left join schools as s on s.refId=osn.schoolRefId
		
		left join offeringDayNodes as odn on odn.offeringRefId=o.refId
		left join days as d on d.refId=odn.dayRefId
		
		where s.name like '%hall%'
		and d.refId='2'
		) as tmp1
	)
);