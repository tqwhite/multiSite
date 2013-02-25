select
c.name,
b.uri,
b.shortId

from categories as c
left join bookmarkCategoryNodes as bcn on bcn.categoryRefId=c.refId
left join bookmarks as b on b.refId=bcn.bookmarkRefId


order by c.created desc;