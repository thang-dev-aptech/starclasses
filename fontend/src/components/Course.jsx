import React, { useState } from "react";
import CourseBox from "./elements/CourseBox";
import teacherList from "../assets/teacherList.json";
import "../assets/css/component.css";

export default function Course() {
  const [selectedSubject, setSelectedSubject] = useState(0);
  const selectedSubjectName = teacherList.subjects[selectedSubject];
  const filteredCourses =
    selectedSubject === 0
      ? teacherList.courses
      : teacherList.courses.filter(
          (course) => course.subject === selectedSubjectName
        );

  return (
    <div className="text-center pt-5" id="course">
      <h1 className="fw-bold display-5 text-capitalize">Our Classes</h1>
      <p className="text-secondary fs-5">
        Explore our wide range of courses designed to help you excel in your
        academic journey.
      </p>
      <div className="row mx-5">
        <div>
          <ul className="d-inline-flex flex-wrap gap-2 bg-body-secondary p-2 border rounded justify-content-center">
            {teacherList.subjects.map((item, index) => (
              <li className="nav-item" key={index}>
                <button
                  className={`btn ${
                    selectedSubject === index ? "btn-dark active" : "btn-light"
                  }`}
                  onClick={() => setSelectedSubject(index)}
                >
                  {item}
                </button>
              </li>
            ))}
          </ul>
        </div>
        <div className="row align-items-stretch mx-2">
          {filteredCourses.map((course) => (
            <div key={course.id} className="col-12 col-md-6 col-lg-4 mb-4">
              <CourseBox course={course} />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
