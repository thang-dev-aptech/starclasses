import React, { useState } from 'react';
import TeacherBox from './elements/TeacherBox';
import teacherList from '../assets/teacherList.json';
import '../assets/css/component.css';

export default function Teacher() {
    const [selectedSubject, setSelectedSubject] = useState(0);
    const selectedSubjectName = teacherList.subjects[selectedSubject];
    const filteredTeachers = selectedSubject === 0 ? teacherList.teachers : teacherList.teachers.filter(
        teacher => teacher.subject === selectedSubjectName
    );

    return (
        <div className="text-center pt-5" id='teacher'>
            <h1 className="fw-bold display-5 text-capitalize">Meet our expert teachers</h1>
            <p className="text-secondary fs-5">Our team of highly qualified educator are dedicated to helping you succeed</p>
            <div className="row mx-5">
                <div>
                    <ul className="d-inline-flex flex-wrap gap-2 bg-body-secondary p-2 border rounded justify-content-center">
                        {teacherList.subjects.map((item, index) => (
                            <li className="nav-item" key={index}>
                                <button
                                    className={`btn ${selectedSubject === index ? 'btn-dark active' : 'btn-light'}`}
                                    onClick={() => setSelectedSubject(index)}
                                >
                                {item}
                                </button>
                            </li>
                        ))}
                    </ul>
                </div>
                <div className="row align-items-stretch mx-2">
                    {filteredTeachers.map((teacher) => (
                        <div key={teacher.id} className="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <TeacherBox teacher={teacher} />
                        </div>
                    ))}
                </div>

            </div>
        </div>
    );
}
