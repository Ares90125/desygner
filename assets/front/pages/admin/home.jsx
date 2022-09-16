import { data } from 'autoprefixer';
import React from 'react';
import { useEffect } from 'react';
import { useCallback } from 'react';
import { useState } from 'react';
import Api from '../../global/Api';

const AdminHome = () => {
    const [mode, setMode] = useState('url');
    const [url, setUrl] = useState('');
    const [image, setImage] = useState(null);
    const [provider, setProvider] = useState('');
    const [tag, setTag] = useState('');

    const [images, setImages] = useState([]);

    const [loading, setLoading] = useState(false);
    const [initial, setInitial] = useState(true);
    const [submitLoading, setSubmitLoading] = useState(false);

    const handleFileSelect = useCallback(({ target }) => {
        const files = target.files;
        if (!files || !files.length) {
            setImage(null);
        } else {
            setImage(files[0])
        }
    }, []);

    const fetchMine = useCallback(() => {
        if (loading) return;
        setLoading(true);
        Api.get('/api/admin/images')
        .then(response => {
            setImages(response);
        })
        .finally(() => setLoading(false));
    }, [loading])

    const handleSubmit = useCallback(() => {
        if (submitLoading) return;
        if (!image && !url) {
            alert('Please input image or url');
            return;
        }
        if (!provider) {
            alert('Provider name is required');
            return;
        }
        const tags = tag.split(',').map(item => item.trim());

        const data = new FormData();
        data.append('provider', provider);
        tags.forEach((tag, index) => {
            data.append('tags[' + index + ']', tag);
        })
        if (mode === 'url') {
            data.append('url', url);
        } else {
            data.append('image', image);
        }
        setSubmitLoading(true);
        Api.post('/api/admin/images', data, {
            'Content-Type': 'multipart/form-data'
        })
        .then(() => {
            // TODO referesh image list
            setUrl('');
            setProvider('');
            setImage(null);
            setTag('');
            alert('Successfully submitted');

            fetchMine();
        })
        .catch(e =>{
            alert(e.getMessage());
        })
        .finally(() => setSubmitLoading(false))
    }, [image, url, provider, tag, fetchMine]);

    useEffect(() => {
        if (initial) {
            setInitial(false);
            fetchMine();
        }
    }, [initial, fetchMine])


    return (
        <div className='flex px-3 pt-5 w-full h-full'>
            <div className='w-96 p-2 pr-4'>
                <h2 className='mb-4 font-bold text-lg'>Add Image</h2>

                <div className='flex flex-col mb-3'>
                    <label className='font-bold'>Mode</label>
                    <div className='flex gap-x-5'>
                        <div>
                            <input type="radio" value="url" checked={mode === 'url'} onChange={(e) => setMode(e.target.value)} />Url
                        </div>
                        <div>
                            <input type="radio" value="blob" checked={mode === 'blob'} onChange={(e) => setMode(e.target.value)} />Blob
                        </div>
                    </div>
                </div>
                {mode === 'url' &&
                    <div className='flex flex-col mb-3'>
                        <label className='font-bold'>Input Url</label>
                        <div>
                            <input type="text" className="w-full border border-black rounded-md" value={url} onChange={e => setUrl(e.target.value)}></input>
                        </div>
                    </div>
                }
                {mode === 'blob' &&
                    <>
                        <div className='flex flex-col mb-3'>
                            <label className='font-bold'>Select Image</label>
                            <div>
                                <input type="file" accept='image/*' onChange={handleFileSelect}></input>
                            </div>
                        </div>
                    </>
                }
                <div className='flex flex-col mb-3'>
                    <label className='font-bold'>Provider Name</label>
                    <div>
                        <input type="text" className="w-full border border-black rounded-md" value={provider} onChange={e => setProvider(e.target.value)}></input>
                    </div>
                </div>
                <div className='flex flex-col mb-3'>
                    <label className='font-bold'>Tags (comma seperated)</label>
                    <div>
                        <input type="text" className="w-full border border-black rounded-md" value={tag} onChange={e => setTag(e.target.value)}></input>
                    </div>
                </div>
                <div className='flex justify-end'>
                    <button className='px-[8px] py-[6px] rounded-lg text-white font-bold' style={{ background: '#6758E3'}} onClick={handleSubmit}>
                        {submitLoading ? 'Submitting...' : 'Submit'}
                    </button>
                </div>
            </div>
            <div className='flex-grow h-full border-l-2'>
                <div className='p-2 pl-4'>
                    <h2 className='mb-4 font-bold text-lg'>My Uploaded Image</h2>
                </div>
                <div className='flex flex-wrap p-2 pl-4 gap-x-3 gap-y-3'>
                    {images.map(image => (
                        <div className='w-48 p-2 border rounded-lg' key={image.id}>
                            <img src={image.url} className='w-full object-cover'/>
                        </div>
                    ))}
                </div>
            </div>

        </div>
    )
}

export default AdminHome;
