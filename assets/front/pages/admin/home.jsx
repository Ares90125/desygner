import React from 'react';
import { useCallback } from 'react';
import { useState } from 'react';

const AdminHome = () => {
    const [url, setUrl] = useState('');
    const [image, setImage] = useState(null);
    const [mode, setMode] = useState('url');
    const [provider, setProvider] = useState('');
    const [tag, setTag] = useState('');

    const handleFileSelect = useCallback(({ target }) => {
        const files = target.files;
        if (!files.length) {
            setImage(null);
        } else {
            setImage(files[0])
        }
    }, [])

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
                            <input type="text" className="w-full border border-black rounded-md" value={url} onChange={e => setUrl(e.target.url)}></input>
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
                        <input type="text" className="w-full border border-black rounded-md" value={provider} onChange={e => setProvider(e.target.url)}></input>
                    </div>
                </div>
                <div className='flex flex-col mb-3'>
                    <label className='font-bold'>Tags (comma seperated)</label>
                    <div>
                        <input type="text" className="w-full border border-black rounded-md" value={tag} onChange={e => setTag(e.target.url)}></input>
                    </div>
                </div>
                <div className='flex justify-end'>
                    <button className='px-[8px] py-[6px] rounded-lg text-white font-bold' style={{ background: '#6758E3'}}>
                        Add
                    </button>
                </div>
            </div>
            <div className='flex flex-grow h-full border-l-2'>
                Images
            </div>

        </div>
    )
}

export default AdminHome;
