export const serializeToQuery = (
  obj,
  prefix
) => {
  const str = [];
  for (const p in obj) {
      if (obj.hasOwnProperty(p)) {
          var k = prefix ? prefix + '[' + p + ']' : p,
              v = obj[p];
          str.push(
              v !== null && typeof v === 'object'
                  ? serializeToQuery(v, k)
                  : encodeURIComponent(k) + '=' + encodeURIComponent(v)
          );
      }
  }
  return str.join('&');
};

export const getUrlWithParam = (
  baseUrl,
  params
) => {
  const Url = new URL(baseUrl);
  Url.search = serializeToQuery(params);
  return Url.toString();
};

export const getAbsoluteUrl = (url, baseUrl = '') => {
  if (url.startsWith('http://') || url.startsWith('https://')) {
      return url;
  }
  if (!url.startsWith('/')) {
      url = `/${url}`;
  }
  return `${baseUrl}${url}`;
};
