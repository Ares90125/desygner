import LocalStorage, { getToken } from "./LocalStorage";
import axios from "axios";
import { getAbsoluteUrl, getUrlWithParam } from '../helpers/url';
import _ from 'lodash';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const baseUrl = process.env.REACT_APP_API_URL;

const precessHeaders = (headers) => {
  if (!headers) headers = {};

  headers = {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      ...headers,
  };

  const token = getToken();
  if (token) {
      headers['Authorization'] = 'Bearer ' + token;
  }
  return headers;
};


export const requestErrorHandler = (e, cb) => {
  if (e.response && e.response.status === 401) {
      LocalStorage.removeToken();
      window.location.href = '/login';
      return;
  }
  if (
      e.response &&
      e.response.data &&
      e.response.data.errors &&
      !_.isEmpty(e.response.data.errors)
  ) {
      let errors = [];
      for (const errorField in e.response.data.errors) {
          errors.push(e.response.data.errors[errorField]);
      }
      cb({ message: errors.join('<br/>') });
  } else if (e.response && e.response.data && e.response.data.message) {
      cb({ message: e.response.data.message, status: e.response.status });
  } else {
      cb(e);
  }
};



const apiWrapper = (
  method,
  url,
  headers,
  data = {}
) => {
  return new Promise(async (resolve, reject) => {
      let postHeader = precessHeaders(headers);

      if (data instanceof Object && !(data instanceof FormData)) {
          data = _.omit(data, 'errors');
      }
      let absUrl = getAbsoluteUrl(url, baseUrl);
      if (method !== 'POST' && method !== 'PUT') {
        absUrl = getUrlWithParam(absUrl, data);
      }

      try {
          let response = null;
          if (method === 'GET') {
              response = await axios.get(absUrl, { headers: postHeader });
          } else if (method === 'POST') {
              response = await axios.post(absUrl, data, {
                  headers: postHeader,
              });
          } else if (method === 'PUT') {
              response = await axios.put(absUrl, data, {
                  headers: postHeader,
              });
          } else {
              response = await axios.delete(absUrl, { headers: postHeader });
          }
          resolve(response.data);
      } catch (e) {
          requestErrorHandler(e, reject);
      }
  });
};


const Api = {
  get: (
      url,
      data,
      headers
  ) => {
      return apiWrapper('GET', url, headers, data);
  },
  post: (
      url,
      data,
      headers
  ) => {
      return apiWrapper('POST', url, headers, data);
  },
  put: (
      url,
      data,
      headers
  ) => {
      return apiWrapper('PUT', url, headers, data);
  },
  delete: (
      url,
      data,
      headers
  ) => {
      return apiWrapper('DELETE', url, headers, data);
  },
};

export default Api;
