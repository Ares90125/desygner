import React from "react";
import { useCallback } from "react";
import { useEffect } from "react";
import { useState } from "react";
import {DebounceInput} from 'react-debounce-input';
import Api from "../../global/Api";

const PAGE_SIZE = 10000;

const UserHome = () => {
    const [images, setImages] = useState([]);
    const [page, setPage] = useState(1);
    const [libraryLoading, setLibraryLoading] = useState(false);

    const [libraryImages, setLibraryImages] = useState([]);

    const [initial, setInitial] = useState(true);

    const [selectedId, setSelectedId] = useState();

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

    const fetchLibraryImages = useCallback(() => {
        if (libraryLoading) return;
        setLibraryLoading(true);
        Api.get('/api/user/library/images')
        .then(response => {
            console.log({response});
            setLibraryImages(response);
        })
        .finally(() => setLibraryLoading(false))
    }, [libraryLoading]);

    const handleAddLibrary = useCallback((id) => {
        Api.put(`/api/user/images/${id}/library`)
        .then(() => {
            fetchLibraryImages();
        })
    }, [fetchLibraryImages]);

    useEffect(() => {
        if (initial) {
            setInitial(false);
            fetchLibraryImages();
        }
    },[initial, fetchLibraryImages])



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
                        <div className={'w-44 rounded-lg cursor-pointer relative ' + (image.id === selectedId ? 'border-2' : '') }
                            key={image.id} style={{ borderColor: '#6758E3' }}
                            onMouseMove={() => setSelectedId(image.id)}
                        >
                            <img src={image.url} className='w-full object-cover'/>
                            {image.id === selectedId &&
                                <button className="text-white p-2 absolute top-1 right-1" style={{ background: '#6758E3' }}
                                    onClick={() => handleAddLibrary(image.id)}
                                >
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                                            width="15px" height="15px" viewBox="0 0 407.096 407.096">
                                        <g>
                                            <g color="white">
                                                <path d="M402.115,84.008L323.088,4.981C319.899,1.792,315.574,0,311.063,0H17.005C7.613,0,0,7.614,0,17.005v373.086
                                                    c0,9.392,7.613,17.005,17.005,17.005h373.086c9.392,0,17.005-7.613,17.005-17.005V96.032
                                                    C407.096,91.523,405.305,87.197,402.115,84.008z M300.664,163.567H67.129V38.862h233.535V163.567z"/>
                                                <path d="M214.051,148.16h43.08c3.131,0,5.668-2.538,5.668-5.669V59.584c0-3.13-2.537-5.668-5.668-5.668h-43.08
                                                    c-3.131,0-5.668,2.538-5.668,5.668v82.907C208.383,145.622,210.92,148.16,214.051,148.16z"/>
                                            </g>
                                        </g>
                                    </svg>
                                </button>
                            }
                        </div>
                    ))}
                </div>
            </div>
            <div className='flex-grow h-full border-l-2'>
                <div className='p-2 pl-4'>
                    <h2 className='mb-4 font-bold text-lg'>My Library{libraryLoading? ' (Loading ...)' : ''}</h2>
                </div>
                <div className='flex flex-wrap p-2 pl-4 gap-x-3 gap-y-3'>
                    {libraryImages.map(image => (
                        <div className='w-48 p-2 border rounded-lg' key={image.id}>
                            <img src={image.url} className='w-full object-cover'/>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    )
}
export default UserHome;
