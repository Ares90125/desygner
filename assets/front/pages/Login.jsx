import React, { useCallback, useState } from "react";
import { useDispatch } from "react-redux";
import { useNavigate } from "react-router";
import Api from "../global/Api";
import { saveToken } from "../global/LocalStorage";
import { setSession } from "../store/reducers/session";

const Login = () => {
  const dispatch = useDispatch();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const getSession = useCallback(() => {
    Api.get('/api/me')
    .then(response => {
        dispatch(setSession(response.user));
        navigate('/');
    })
  }, [])

  const handleSubmit = useCallback(() => {
    if (loading) return;
    if (!email || !password) return;
    Api.post('api/login_check', {
        email, password
    })
    .then(response => {
        const { token } = response;
        saveToken(token);
        getSession();
    })
    .finally(() => {
        setLoading(false);
    })
  }, [loading, email, password, loading, getSession])

  return (
    <div className="bg-gray-100">
      <section className="h-screen w-full flex items-center justify-center">
        <div className="flex h-auto shadow-md overflow-hidden rounded w-full md:max-w-screen-lg">
          <div className="p-12 flex flex-col items-center justify-center w-1/2" style={{ background: '#6758E3'}}>
            <h1 className="lato font-bold text-4xl text-white tracking-widest mb-8">
                Desygner
            </h1>
          </div>
          <div className=" bg-white p-12 h-auto w-1/2">
            <h1 className="mb-8 text-center font-black text-xl">
                Sign In
            </h1>
            <div className="mb-8">
                <div className="mb-8">
                    <input
                        className="text-sm border border-gray-400 rounded_ p-2 w-full" type="email" placeholder="Email"
                        value={email}
                        onChange={(e) => {
                          setEmail(e.target.value)
                        }}
                    />
                </div>
                <div>
                    <input
                        className="text-sm border border-gray-400 rounded_ p-2 w-full"
                        type="password"
                        placeholder="Password"
                        value={password}
                        onChange={(e) => {
                          setPassword(e.target.value);
                        }}
                    />
                </div>
            </div>
            <div className=" text-center mb-8">
                <button className=" bg-red-600 rounded py-2 text-lg text-white w-full px-8" onClick={handleSubmit}>
                    Login
                </button>
            </div>
          </div>
        </div>
      </section>
    </div>
  )
}
export default Login;
