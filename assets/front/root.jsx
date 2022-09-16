import React from 'react';
import { useState } from 'react';
import { useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate, useLocation } from 'react-router';
import Api from './global/Api';
import LocalStorage from './global/LocalStorage';
import { setSession } from './store/reducers/session';
import { sessionSelector } from './store/selectors/session';

const Root = (props) => {
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const location = useLocation();

    const { data: session } = useSelector(sessionSelector);

    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (loading) return;
        const publicPaths = ['/login'];
        if (publicPaths.includes(location.pathname)) {
            if (session) {
                if (session.roles.includes('ROLE_ADMIN')) {
                    navigate('/admin/home'); return;
                } else if (session.roles.includes('ROLE_DEV')) {
                    navigate('/dev/home'); return;
                } else if (session.roles.includes('ROLE_USER')) {
                    navigate('/user/home'); return;
                }
            }
            return;
        }
        const token = LocalStorage.getToken();
        if (!token && !session) {
            navigate('/login');
            return;
        } else if (token && !session) {
            if (loading) return;
            setLoading(true);
            Api.get('/api/me')
            .then(response => {
              dispatch(setSession(response.user));
            })
            .finally(() => {
                setLoading(false);
            });
        } else if (session)  {
            if (session.roles.includes('ROLE_ADMIN') && location.pathname !== '/admin/home') {
                navigate('/admin/home'); return;
            } else if (session.roles.includes('ROLE_DEV') && location.pathname !== '/dev/home') {
                navigate('/dev/home'); return;
            } else if (session.roles.includes('ROLE_USER') && location.pathname !== '/user/home') {
                navigate('/user/home'); return;
            }
        }
    }, [dispatch, loading, location.pathname, navigate, session]);

    if (loading) {
        return (
            <div className='flex justify-center align-center w-full h-full absolute'>
                Loading ...
            </div>
        )
    }
    return <>{props.children}</>
}
export default Root;
