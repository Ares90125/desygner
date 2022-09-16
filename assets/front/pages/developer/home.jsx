import React from "react";
import { useCallback } from "react";
import { useState } from "react";
import {DebounceInput} from 'react-debounce-input';
import Api from "../../global/Api";

const PAGE_SIZE = 10000;

const DeveloperHome = () => {
    const [images, setImages] = useState([]);
    const [page, setPage] = useState(1);

    const handleSearch = useCallback((e) => {
        const search = e.target.value;
        const query = {
            page,
            size: PAGE_SIZE
        };
        if (search) {
            query.q = search;
        }
        Api.get('/api/images', query)
        .then(response => {
            setImages(response);
            // TODO we can update page for infinite scroll
            // setPage(old => old + 1);
        })
    }, [page])

    return (
        <div className='flex px-3 pt-5 w-full h-full'>
            <div className='w-96 p-2 pr-4 h-full'>
                <h2 className='mb-4 font-bold text-lg'>Search Image</h2>
                <div className="w-full mb-5">
                    <DebounceInput
                        className="w-full border border-black rounded-md"
                        minLength={2}
                        debounceTimeout={500}
                        onChange={handleSearch}
                    />
                </div>
                <div className="flex flex-wrap gap-x-2 gap-y-3">
                    {images.map(image => (
                        <div className='w-44 border rounded-lg' key={image.id}>
                            <img src={image.url} className='w-full object-cover'/>
                        </div>
                    ))}
                </div>
            </div>
            <div className='flex-grow h-full border-l-2'>

            </div>
        </div>
    )
}
export default DeveloperHome;
