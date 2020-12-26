import '@testing-library/jest-dom/extend-expect';
import { screen } from '@testing-library/react';
import React from 'react';
import { act } from 'react-dom/test-utils';
import App from './App';
import api from './services/api';
import { postsResponseMock, renderWithRouter, sessionsResponseMock } from './utils/test.utils';

jest.mock('./services/api');
const mockedApi = api as jest.Mocked<typeof api>;

afterEach(() => {
  jest.resetAllMocks();
  localStorage.clear();
});

async function renderHomeComponent() {
  await act(async () => {
    renderWithRouter(<App />);
    (await screen.findByText(/Log In/i)).click();
  });
}

describe('Login page', () => {
  it('renders component', async () => {
    mockedApi.post.mockImplementation(() => Promise.resolve(sessionsResponseMock.post.success));
    const { getByText } = renderWithRouter(<App />);
    expect(getByText(/Log In/i)).toBeInTheDocument();
  });
});

describe('Homepage page', () => {
  test('renders component', async () => {
    mockedApi.post.mockImplementation(() => Promise.resolve(sessionsResponseMock.post.success));
    mockedApi.get.mockImplementation(() => Promise.resolve(postsResponseMock.get.success));
    await renderHomeComponent();
    expect(screen.getByText(/Home/i)).toBeInTheDocument();
  });

  test('Authentication', async () => {
    mockedApi.post.mockImplementation(() => Promise.resolve(sessionsResponseMock.post.success));
    mockedApi.get.mockImplementation(() => Promise.resolve(postsResponseMock.get.success));
    await renderHomeComponent();
    const token = localStorage.getItem('@App:token');
    expect(token).not.toBeNull();
    const user = localStorage.getItem('@App:user');
    expect(user).not.toBeNull();
    expect(api.defaults.headers.common['Authorization']).toBe(`Bearer ${token}`);
  });
});

describe('404 page', () => {
  it('renders 404 component', async () => {
    mockedApi.post.mockImplementation(() => Promise.resolve(sessionsResponseMock.post.failure));
    renderWithRouter(<App />, '/404');
    expect(screen.getByText(/Not Found/i)).toBeInTheDocument();
  });
});

describe('Explore page', () => {
  it('renders Explore component', async () => {
    mockedApi.post.mockImplementation(() => Promise.resolve(sessionsResponseMock.post.success));
    mockedApi.get.mockImplementation(() => Promise.resolve(postsResponseMock.get.success));
    await renderHomeComponent();
    (await screen.findByText(/Explore/i)).click();
    expect(screen.getByText(/Explore/i)).toBeInTheDocument();
  });
});
